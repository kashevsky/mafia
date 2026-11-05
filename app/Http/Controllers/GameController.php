<?php

namespace App\Http\Controllers;

use App\Http\Helpers\TgHelper;
use App\Models\Account;
use App\Models\AdminClub;
use App\Models\Application;
use App\Models\Club;
use App\Models\Game;
use App\Models\PayEntity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class GameController extends Controller
{


    public function index()
    {
        $account = session()->get('account');
        $account = Account::find($account->id);

        // Подгружаем клуб
        $club = Club::with('games')->find($account->club_id);
        $club->slides = $club->games->whereNotNull('is_in_slider')->where('status', 'pending');

        $accounts = Account::with('club')->where('club_id', $account->club_id)->get();

        // --- НАЧАЛО БЛОКА ДЛЯ ПРОШЕДШИХ ИГР ---
        // Выбираем строго последние 7 закрытых игр этого клуба, сортируем по дате (новые сверху)
        $past_games_grouped = $club->games() // предполагается связь hasMany('Game')
            ->where('status', 'closed')
            ->orderByDesc('date')
            ->take(7)
            ->get()
            ->groupBy(function ($game) {
                // Группируем коллекции прямо в PHP по формату даты d.m.Y
                return Carbon::parse($game->date)->format('d.m.Y');
            });
        // --- КОНЕЦ БЛОКА ДЛЯ ПРОШЕДШИХ ИГР ---

        return view('index', compact('account', 'club', 'accounts', 'past_games_grouped'));
    }

    public function changeStatus(Request $request)
    {
        $params = $request->all();

        $game = Game::find($params['game_id']);
        $game->status = $params['status'];
        if ($params['status'] == 'closed') {
            $game->finished_at = now();
            $game->is_closed = 1;

            $game->is_closed = 1;
            $game->status = $params['status'];
            $game->save();
            $payEntities = PayEntity::where('game_id', $game->id)->get();
            foreach ($payEntities as $payEntity) {
                $payEntity->is_confirmed = 1;
                $payEntity->save();
            }
        }
        $game->save();
        return json_encode(['success' => true]);
    }

    public function show($id)
    {
        $account = session()->get('account');
        // $aacounts = Account::get();
        // foreach ($aacounts as $account) {
        //     $account->avatar = null;
        //     $account->save();
        // }

        $account = Account::find($account->id);
        $club = Club::with('games')->find($account->club_id);
        $game = Game::with('club', 'photos', 'applications')->find($id);

        if ($game->club_id != $account->club_id) {
            abort(404);
        }


        return view('event', compact('account', 'club', 'game'));
    }

    public function booking(Request $request)
    {
        $params = $request->all();
        $gameId = $params['game_id'] ?? null;
        $accountId = $params['account_id'] ?? null;
        $quantity = (int)($params['quantity'] ?? 1);

        if (!$gameId || !$accountId) {
            return response()->json(['success' => false, 'message' => 'Некорректные параметры запроса'], 400);
        }

        try {
            // Запускаем транзакцию, чтобы защититься от Race Condition
            $application = DB::transaction(function () use ($gameId, $accountId, $quantity) {

                // 1. Блокируем строку игры для чтения/записи другими параллельными запросами
                $game = Game::where('id', $gameId)->lockForUpdate()->first();

                if (!$game) {
                    // Выбрасываем исключение, чтобы прервать трансляцию
                    throw new \Exception('Игра не найдена', 404);
                }

                // 2. Быстрая проверка на дубликат записи
                $exists = Application::where('account_id', $accountId)
                    ->where('game_id', $gameId)
                    ->exists();

                if ($exists) {
                    throw new \Exception('Вы уже записаны на эту игру', 422);
                }

                // 3. Считаем занятые места внутри заблокированной транзакции
                $totalSpace = (int) ($game->total_space ?? 0);
                $bookedRows = Application::where('game_id', $gameId)->count();
                $bookedDops = (int) Application::where('game_id', $gameId)->sum('dops');
                $occupiedSeats = $bookedRows + $bookedDops;

                $availableSeats = $totalSpace - $occupiedSeats;

                // 4. Проверяем наличие свободных мест
                if ($quantity > $availableSeats) {
                    $message = $availableSeats > 0
                        ? "Извините, осталось только мест: {$availableSeats}. Измените количество."
                        : "Извините, все места на эту игру уже заняты!";
                    throw new \Exception($message, 422);
                }

                // Вычисляем допы
                $dops = $quantity > 1 ? $quantity - 1 : 0;

                // 5. Создаем запись в базе данных
                return Application::create([
                    'account_id' => $accountId,
                    'game_id'    => $gameId,
                    'dops'       => $dops,
                ]);
            });
        } catch (\Exception $e) {
            // Если внутри транзакции произошла ошибка, возвращаем JSON с правильным HTTP-кодом
            $code = in_array($e->getCode(), [404, 422]) ? $e->getCode() : 400;
            return response()->json(['success' => false, 'message' => $e->getMessage()], $code);
        }

        // --- Логика уведомлений (вынесена ЗА пределы транзакции, чтобы не тормозить БД внешними API-запросами) ---
        // Пересохраняем инстанс игры для отправки текста
        $game = Game::find($gameId);
        $account = Account::find($accountId);

        if ($account && $game) {
            $text = 'Новая заявка на игру!' . PHP_EOL;
            $text .= 'Игра: ' . $game->title . PHP_EOL;

            $nameToShow = !empty($account->nickname) ? $account->nickname : $account->name;
            $tgShow = !empty($account->telegram_username) ? ' (@' . $account->telegram_username . ')' : '';
            $text .= 'Игрок: ' . $nameToShow . $tgShow . PHP_EOL;

            $dops = $quantity > 1 ? $quantity - 1 : 0;
            if ($quantity > 1) {
                $text .= 'Забронировано мест: ' . $quantity . ' (Игрок + ' . $dops . ' доп.)' . PHP_EOL;
            } else {
                $text .= 'Забронировано мест: 1' . PHP_EOL;
            }

            if (!empty($account->club_id)) {
                $admins = AdminClub::with('admin')->where('club_id', $account->club_id)->get();
                foreach ($admins as $admin) {
                    if (!empty($admin->admin->telegram_id)) {
                        try {
                            TgHelper::sendMessage($text, $admin->admin->telegram_id);
                        } catch (\Exception $e) {
                            logger()->error("Ошибка Telegram API: " . $e->getMessage());
                        }
                    }
                }
            }
        }

        return response()->json(['success' => true]);
    }



    public function cancelBooking(Request $request)
    {
        $gameId = $request->input('game_id');
        $accountId = $request->input('account_id');

        // 1. Проверяем обязательные параметры
        if (!$gameId || !$accountId) {
            return response()->json(['success' => false, 'message' => 'Некорректные параметры запроса'], 400);
        }

        // 2. Ищем заявку
        $application = Application::where('account_id', $accountId)
            ->where('game_id', $gameId)
            ->first();

        if (!$application) {
            return response()->json(['success' => false, 'message' => 'Запись не найдена'], 404);
        }

        // Запоминаем, сколько всего мест освобождается (1 сам юзер + его допы)
        $totalFreedSeats = 1 + ((int) ($application->dops ?? 0));

        // Ищем связанные сущности ДО удаления, чтобы подстраховаться
        $game = Game::find($gameId);
        $account = Account::find($accountId);

        // УДАЛЯЕМ запись
        $application->delete();

        // 3. Формируем и отправляем уведомление (только если игра и аккаунт существуют)
        if ($game && $account) {
            $text = '❌ Отмена заявки на игру!' . PHP_EOL;
            $text .= 'Игра: ' . ($game->title ?? 'Без названия') . PHP_EOL;

            $nameToShow = !empty($account->nickname) ? $account->nickname : $account->name;
            if (!empty($account->telegram_username)) {
                $text .= 'Игрок: ' . $nameToShow . ' (@' . $account->telegram_username . ')' . PHP_EOL;
            } else {
                $text .= 'Игрок: ' . $nameToShow . PHP_EOL;
            }

            if ($totalFreedSeats > 1) {
                $text .= 'Освобождено мест: ' . $totalFreedSeats . PHP_EOL;
            }

            if (!empty($account->club_id)) {
                $admins = AdminClub::with('admin')->where('club_id', $account->club_id)->get();
                foreach ($admins as $admin) {
                    if (!empty($admin->admin->telegram_id)) {
                        try {
                            // Оборачиваем в try-catch, чтобы ошибка Telegram не ломала ответ пользователю
                            TgHelper::sendMessage($text, $admin->admin->telegram_id);
                        } catch (\Exception $e) {
                            logger()->error("Ошибка отправки ТГ при отмене: " . $e->getMessage());
                        }
                    }
                }
            }
        }

        // Возвращаем количество удаленных мест, чтобы фронтенд правильно обновил счетчик
        return response()->json([
            'success' => true,
            'freed_seats' => $totalFreedSeats
        ]);
    }


    public function results($id)
    {
        $game = Game::with('results')->findOrFail($id);

        // Сначала загружаем результаты с аккаунтами, потом берём топ‑3
        $results = $game->results()
            ->with('account')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        $topPlayers = $results->map(function ($result) {
            // Проверяем, что аккаунт вообще есть
            if (!$result->account) {
                return null;
            }

            $account = $result->account;
            $account->total = $result->total;
            return $account;
        })
            ->filter(); // убираем null, если вдруг какой-то результат без аккаунта

        $account = session()->get('account');
        $account = Account::find($account->id);
        $club = Club::find($account->club_id);


        return view('results', compact('game', 'topPlayers', 'account', 'club'));
    }


    public function updateBooking(Request $request)
    {
        // 1. Валидация входящих параметров
        $validated = $request->validate([
            'account_id' => ['required', 'integer'],
            'game_id'    => ['required', 'integer'],
            'dops'       => ['required', 'integer', 'min:0'],
        ]);

        $accountId = $validated['account_id'];
        $gameId = $validated['game_id'];
        $newDops = (int)$validated['dops'];

        // Переменные для хранения старого значения допов (нужно для текста уведомления)
        $oldDops = 0;

        try {
            // 2. Выполняем обновление в транзакции с блокировкой строк
            $result = DB::transaction(function () use ($gameId, $accountId, $newDops, &$oldDops) {
                // Блокируем игру
                $game = Game::where('id', $gameId)->lockForUpdate()->first();
                if (!$game) {
                    throw new \Exception('Игра не найдена', 404);
                }

                // Блокируем саму заявку
                $application = Application::where('game_id', $gameId)
                    ->where('account_id', $accountId)
                    ->lockForUpdate()
                    ->first();

                if (!$application) {
                    throw new \Exception('Запись не найдена', 404);
                }

                $oldDops = (int)$application->dops;

                // Если количество допов не изменилось, прерываем, чтобы не делать лишних расчетов
                if ($oldDops === $newDops) {
                    return [
                        'old_quantity' => $oldDops + 1,
                        'new_quantity' => $newDops + 1,
                    ];
                }

                // Считаем текущие занятые места (сумма строк + сумма допов)
                $bookedRows = Application::where('game_id', $gameId)->count();
                $bookedDops = (int)Application::where('game_id', $gameId)->sum('dops');
                $currentOccupied = $bookedRows + $bookedDops;

                // Рассчитываем, сколько мест будет занято после обновления допов
                // (вычитаем старые допы этого юзера и прибавляем новые)
                $newOccupiedSeats = $currentOccupied - $oldDops + $newDops;
                $totalSpace = (int)($game->total_space ?? 0);

                // Проверяем, влезаем ли в лимит комнат/мест
                if ($newOccupiedSeats > $totalSpace) {
                    $availableSeats = $totalSpace - ($currentOccupied - $oldDops);
                    $message = $availableSeats > 0
                        ? "Недостаточно свободных мест. Доступно допов для вас: " . ($availableSeats - 1)
                        : "Извините, свободных мест больше нет.";
                    throw new \Exception($message, 422);
                }

                // Обновляем количество допов
                $application->update([
                    'dops' => $newDops,
                ]);

                return [
                    'old_quantity' => $oldDops + 1,
                    'new_quantity' => $newDops + 1,
                ];
            });
        } catch (\Exception $e) {
            // Ловим исключения из транзакции и возвращаем корректный JSON-ответ
            $code = in_array($e->getCode(), [404, 422]) ? $e->getCode() : 400;
            return response()->json(['success' => false, 'message' => $e->getMessage()], $code);
        }

        // --- Логика уведомлений (вынесена ЗА пределы транзакции) ---
        $game = Game::find($gameId);
        $account = Account::find($accountId);

        if ($account && $game) {
            $text = '🔄 Обновление заявки на игру!' . PHP_EOL;
            $text .= 'Игра: ' . ($game->title ?? 'Без названия') . PHP_EOL;

            $nameToShow = !empty($account->nickname) ? $account->nickname : $account->name;
            $tgShow = !empty($account->telegram_username) ? ' (@' . $account->telegram_username . ')' : '';
            $text .= 'Игрок: ' . $nameToShow . $tgShow . PHP_EOL;

            // Красивый вывод изменения количества мест
            $oldTotal = $result['old_quantity'];
            $newTotal = $result['new_quantity'];
            $text .= "Изменено мест: {$oldTotal} ➡️ {$newTotal} (Игрок + {$newDops} доп.)" . PHP_EOL;

            if (!empty($account->club_id)) {
                $admins = AdminClub::with('admin')->where('club_id', $account->club_id)->get();
                foreach ($admins as $admin) {
                    if (!empty($admin->admin->telegram_id)) {
                        try {
                            TgHelper::sendMessage($text, $admin->admin->telegram_id);
                        } catch (\Exception $e) {
                            logger()->error("Ошибка Telegram API при обновлении: " . $e->getMessage());
                        }
                    }
                }
            }
        }

        return response()->json([
            'success'      => true,
            'old_quantity' => $result['old_quantity'],
            'new_quantity' => $result['new_quantity'],
            'dops'         => $newDops,
        ]);
    }
}
