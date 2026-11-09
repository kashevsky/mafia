<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AuthHelper;
use App\Http\Helpers\TgHelper;
use App\Models\Account;
use App\Models\AccountImmunity;
use App\Models\AdminClub;
use App\Models\Application;
use App\Models\Club;
use App\Models\Game;
use App\Models\GamePhoto;
use App\Models\GameStep;
use App\Models\PayEntity;
use App\Models\Table;
use App\Models\TempPublic;
use AppendIterator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GameController extends Controller
{

    public function index()
    {

        $role = AuthHelper::getRole();
        $club_id = AuthHelper::getClubId();
        $admin = AuthHelper::getAdmin();
        $permissions = $admin->permissions;
        $clubs = Club::get();

        $admin_clubs = AdminClub::where('admin_id', $admin->id)->pluck('club_id')->toArray();

        $clubs = Club::whereIn('id', $admin_clubs)->get();
        $club_ids = $clubs->pluck('id')->toArray();

        $games = Game::with('applications', 'newApplications')->whereIn('club_id', $club_ids)->get();

        if ($admin->is_main_admin) {
            $games = Game::with('applications', 'newApplications')->get();
        }
        // Фильтр по клубу, если нужен
        // if ($role === 'admin' && $club_id) {
        //     $games = $games->where('club_id', $club_id);
        // }

        // Получаем все игры


        // Сортируем коллекцию:
        // 1. Будущие — ближе к сегодня сверху
        // 2. Прошедшие — от свежих к старым (внизу)
        $games = $games->sortBy([
            [
                fn($game) => $game->date < now()->format('Y-m-d') ? 1 : 0, // прошедшие в конец
                'asc'
            ],
            [
                fn($game) => $game->date,
                'asc' // среди будущих — по возрастанию даты
            ]
        ]);


        return view('admin.game.index', compact('games', 'clubs', 'admin', 'permissions'));
    }


    public function create()
    {
        $admin = AuthHelper::getAdmin();
        $clubs = Club::get();
        $permissions = $admin->permissions;
        return view('admin.game.create', compact('admin', 'clubs', 'permissions'));
    }
    public function edit($id)
    {
        $game = Game::with('applications', 'tables')->find($id);



        $role = AuthHelper::getRole();
        $club_id = AuthHelper::getClubId();
        $role = AuthHelper::getRole();
        $admin = AuthHelper::getAdmin();
        $permissions = $admin->permissions;


        if ($role == 'admin') {
            if ($game->club_id != $club_id) {
                // return redirect()->route('admin.club.index');
            }
        }
        $clubs = Club::get();
        return view('admin.game.edit', compact('game', 'admin', 'permissions', 'clubs'));
    }

    public function store(Request $request)
    {
        $params = $request->all();

        // $club_id = AuthHelper::getClubId();

        $club_id = $params['club_id'];


        // Подготовим данные для создания
        $gameData = [
            'title' => $request->input('title'),
            'club_id' => $club_id,
            'price' => $request->input('price'),
            'date' => $request->input('event_date'),
            'time' => $request->input('event_time'),
            'total_space' => $request->input('seats'),
            'description' => $request->input('description'),
            'address' => $request->input('address'),

        ];

        // Обработка аватара
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Сохраняем файл в public/uploads/games/avatars
            $filename = $file->hashName();
            $file->move(public_path('uploads/games/avatars'), $filename);

            // Формируем путь с / в начале
            $gameData['avatar'] = '/uploads/games/avatars/' . $filename;
        }

        // Создаём игру
        $game = Game::create($gameData);

        $game->is_in_slider = !empty($params['is_in_slider']) ? 1 : null;

        // --- Сохранение обложки (cover) ---
        if ($request->hasFile('cover')) {
            $dir = public_path('uploads/games/covers');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // 3. Делаем безопасное имя (хэш + расширение), чтобы не было дублей и ошибок
            $file = $request->file('cover');
            $ext = $file->getClientOriginalExtension();
            $filename = md5($file->getRealPath() . time()) . '.' . $ext;
            $fullPath = $dir . '/' . $filename;

            // 4. Сохраняем через file_put_contents (как ты просил)
            file_put_contents($fullPath, file_get_contents($file->getRealPath()));

            // 5. Записываем путь в БД (относительный, с слэшем в начале)
            $game->cover = '/uploads/games/covers/' . $filename;
        } elseif (!empty($params['remove_cover'])) {
            // Если стоит галочка «удалить» — удаляем и ставим null
            if ($game->cover) {

                $game->cover = null;
            }
        }
        $game->save();

        // Загрузка слайдов
        if ($request->hasFile('slides')) {
            foreach ($request->file('slides') as $file) {
                if ($file->isValid()) {
                    // Генерируем уникальное имя
                    $name = $file->hashName(); // например: a1b2c3d4.jpg
                    $file->move(public_path('uploads/slides'), $name);

                    GamePhoto::create([
                        'src' => 'uploads/slides/' . $name,
                        'game_id' => $game->id,
                    ]);
                }
            }
        }


        return redirect()->route('admin.game.edit', $game->id)->with('success', 'Игра успешно создана!');
    }



    public function update(Request $request)
    {
        $params = $request->all();

        $game = Game::findOrFail($params['game_id']);

        // --- Обычные поля (как у тебя было — просто и понятно) ---
        if (isset($params['title'])) {
            $game->title = $params['title'];
        }

        if (isset($params['price'])) {
            $game->price = $params['price'];
        }
        if (isset($params['total_space'])) {
            $game->total_space = $params['total_space'];
        }
        if (isset($params['event_date'])) {
            $game->date = $params['event_date'];
        }
        if (isset($params['club_id'])) {
            $game->club_id = $params['club_id'];
        }
        if (isset($params['seats'])) {
            $game->seats = $params['seats'];
        }
        if (isset($params['event_time'])) {
            $game->time = $params['event_time'];
        }
        if (isset($params['description'])) {
            $game->description = $params['description'];
        }
        if (isset($params['address'])) {
            $game->address = $params['address'];
        }


        // is_in_slider
        $game->is_in_slider = !empty($params['is_in_slider']) ? 1 : null;

        // --- Сохранение обложки (cover) ---
        if ($request->hasFile('cover')) {
            $dir = public_path('uploads/games/covers');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // 3. Делаем безопасное имя (хэш + расширение), чтобы не было дублей и ошибок
            $file = $request->file('cover');
            $ext = $file->getClientOriginalExtension();
            $filename = md5($file->getRealPath() . time()) . '.' . $ext;
            $fullPath = $dir . '/' . $filename;

            // 4. Сохраняем через file_put_contents (как ты просил)
            file_put_contents($fullPath, file_get_contents($file->getRealPath()));

            // 5. Записываем путь в БД (относительный, с слэшем в начале)
            $game->cover = '/uploads/games/covers/' . $filename;
        } elseif (!empty($params['remove_cover'])) {
            // Если стоит галочка «удалить» — удаляем и ставим null
            if ($game->cover) {

                $game->cover = null;
            }
        }

        // --- Сохранение аватара (avatar) ---
        if ($request->hasFile('avatar')) {

            $dir = public_path('uploads/games/avatars');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }


            $file = $request->file('avatar');
            $ext = $file->getClientOriginalExtension();
            $filename = md5($file->getRealPath() . time()) . '.' . $ext;
            $fullPath = $dir . '/' . $filename;
            file_put_contents($fullPath, file_get_contents($file->getRealPath()));
            $game->avatar = '/uploads/games/avatars/' . $filename;
        } elseif (!empty($params['avatar_from_photos'])) {
            // Выбор из существующих фото
            $game->avatar = $params['avatar_from_photos'];
        }

        // --- Дополнительные фото (slides) ---
        if ($request->hasFile('slides')) {
            foreach ($request->file('slides') as $file) {
                $path = $file->store('slides', 'public');
                GamePhoto::create([
                    'src'     => $path,
                    'game_id' => $game->id,
                ]);
            }
        }

        $game->save();

        return redirect()->route('admin.game.index')
            ->with('success', 'Игра успешно обновлена');
    }




    public function confirmPlayers(Request $request)
    {

        $params = $request->all();

        $applications_ids = $params['player_ids'];
        $applications = Application::whereIn('id', $applications_ids)->get();

        foreach ($applications as $application) {
            $application->is_approved = true;
            $application->save();
        }

        return redirect()->back();
    }

    public function delete($id)
    {
        $params = request()->all();
        $game = Game::find($id);
        $game->delete();
        return redirect()->back();
    }
    public function slideDelete($id)
    {
        try {
            // Находим фото по ID
            $slide = GamePhoto::findOrFail($id);

            // Получаем путь к файлу
            $filePath = public_path($slide->src);

            // Удаляем файл с сервера, если он существует
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Удаляем запись из базы данных
            $slide->delete();

            // Возвращаем ответ для AJAX-запроса (если используется)
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Фото успешно удалено'
                ]);
            }

            // Редирект обратно на страницу редактирования игры
            return redirect()
                ->back()
                ->with('success', 'Фото успешно удалено');
        } catch (\Exception $e) {


            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка при удалении фото'
                ], 500);
            }

            return redirect()
                ->back()
                ->withErrors(['error' => 'Ошибка при удалении фото']);
        }
    }

    public function storeScreenshot(Request $request)
    {
        // 1. Валидация входных данных
        $request->validate([
            'screenshot' => 'required|string',
            'table_id'   => 'required|integer',
        ]);


        $table = Table::find($request->input('table_id'));
        if (!$table) {
            return response()->json(['message' => 'Стол не найден'], 444);
        }

        // Определяем фоновое изображение в зависимости от исхода игры
        if ($table->is_mafia_win == 0) {
            $src = '/mir_.jpg';
        } else {
            $src = '/maf_.jpg';
        }
        $src = env('APP_URL') . $src;

        $dataUrl = $request->input('screenshot');

        if (!preg_match('/^data:image\/(png|jpeg);base64,/', $dataUrl)) {
            return response()->json(['message' => 'Неверный формат изображения'], 422);
        }

        $base64Data = substr($dataUrl, strpos($dataUrl, ',') + 1);
        $binaryData = base64_decode($base64Data);

        $filename = 'screenshot_' . Str::random(10) . '.png';
        $filePath = public_path('screenshots/' . $filename);

        if (file_put_contents($filePath, $binaryData) === false) {
            return response()->json(['message' => 'Ошибка записи файла'], 500);
        }

        $accounts = Account::where('table_id', $table->id)->get();
        $accounts_ids = $accounts->pluck('id')->toArray();

        // Снимаем активные иммунитеты игроков за этим столом
        AccountImmunity::whereIn('account_id', $accounts_ids)
            ->where('is_active', 1)
            ->delete();

        // Формируем базовый URL приложения
        $appUrl = rtrim(env('APP_URL'), '/');

        // ИСПРАВЛЕНО: Формируем реальную ссылку на сохраненный скриншот
        $url_ = $appUrl . '/screenshots/' . $filename;
        // Ищем сохраненный ранее текстовый слепок раунда
        $snap = TempPublic::where('table_id', $table->id)
            ->where('step', $table->step)
            ->first();

        // ИСПРАВЛЕНО: Меняем данные в базе ТОЛЬКО если запись реально найдена
        if ($snap) {
            $snap->src = $url_;
            $snap->save();
        }


        foreach ($accounts as $account) {
            if (!$account->telegram_id) {
                continue;
            }



            // Очищаем текст от ссылки <a>, так как она теперь будет полноценной кнопкой под фото
            $message = "📊 <b>Результаты игры стола №{$table->id} (Игра {$table->step})</b>";

            if ($snap) {
                $resultUrl = $appUrl . '/temp-public/' . $snap->id;
                $downloadUrl = $appUrl . '/save-results/' . $snap->id;

                // Отправляем фото, кнопку Web App и новую кнопку для скачивания
                TgHelper::sendPhotoWithWebApp(
                    $account->telegram_id,
                    $src,
                    $message,
                    '📊 Открыть результаты',
                    $resultUrl,
                    $downloadUrl
                );
            } else {
                // Резервный вариант, если слепка игры нет
                TgHelper::sendPhotoWithCaption($account->telegram_id, $src, $message);
            }
        }

        return response()->json([
            'url' => $url_,
            'filename' => $filename,
        ]);
    }



    public function storeScreenshotFinish(Request $request)
    {
        $request->validate(['screenshot' => 'required|string']);

        $dataUrl = $request->input('screenshot');

        if (!preg_match('/^data:image\/(png|jpeg);base64,/', $dataUrl)) {
            return response()->json(['message' => 'Неверный формат изображения'], 422);
        }

        $base64Data = substr($dataUrl, strpos($dataUrl, ',') + 1);
        $binaryData = base64_decode($base64Data);

        // Папка должна существовать: mkdir(public_path('screenshots'), 0755, true);
        $filename = 'screenshot_' . Str::random(10) . '.png';
        $filePath = public_path('screenshots/' . $filename);

        if (file_put_contents($filePath, $binaryData) === false) {
            return response()->json(['message' => 'Ошибка записи файла'], 500);
        }

        // Прямой URL (без storage:link)
        $url = url('screenshots/' . $filename);
        $game = Game::find($request->input('game_id'));
        $game->is_published = 1;
        $game->save();


        $accounts = Account::where('game_id', $game->id)->get();
        $url_ = 'screenshots/' . $filename;

        foreach ($accounts as $account) {
            $account->rating_before_game = 0;
            if (!$account->telegram_id) {
                continue;
            }
            $text = '<b>Результаты игрового вечера</b>';


            TgHelper::sendFinishRating($account->telegram_id, $url_, $text);
        }

        return response()->json([
            'url' => $url,
            'filename' => $filename,
        ]);
    }

    public function skip($table_id)
    {

        $step = Table::find($table_id)->step;
        $game_steps = GameStep::where('step', $step)
            ->where('table_id', $table_id)
            ->get();
        foreach ($game_steps as $game_step) {
            $game_step->delete();
        }

        $accounts = Account::where('table_id', $table_id)->get();
        foreach ($accounts as $account) {
            // $account->rating = $account->rating_before_game;
            // $account->rating_before_game = 0;
            // $account->save();
        }

        return json_encode(['status' => 'success']);
    }
}
