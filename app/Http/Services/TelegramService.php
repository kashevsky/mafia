<?php

namespace App\Http\Services;

use App\Mail\GameRoleMail;
use App\Services\MafiaImageService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TelegramService
{
    protected string $token = '8338210523:AAGZ_njZqRpbwVl8RoOoK3Dt5g1sQKLrovI';

    protected string $apiUrl = 'https://api.telegram.org/bot8338210523:AAGZ_njZqRpbwVl8RoOoK3Dt5g1sQKLrovI';


    /**
     * Отправка обычного текстового сообщения
     */
    public function sendMessage(string $chatId, string $text, array $options = []): bool
    {

        return $this->request('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
            ...$options,
        ]);
    }

    /**
     * Отправка фото (картинки)
     * $photo может быть: URL строкой ИЛИ локальным путем к файлу (например, storage/app/public/img.jpg)
     */
    public function sendPhoto(string $chatId, $photo, string $caption = '', array $options = []): bool
    {
        // Объединяем базовые параметры с дополнительными через array_merge
        $payload = array_merge([
            'chat_id' => $chatId,
            'caption' => $caption,
        ], $options);

        // Если передан путь к файлу, отправляем как multipart/form-data (файл)
        if (is_string($photo) && file_exists($photo)) {
            $file = fopen($photo, 'r');
            $payload['photo'] = $file;

            return $this->multipartRequest('sendPhoto', $payload);
        }

        // Иначе считаем, что это URL
        $payload['photo'] = $photo;
        return $this->request('sendPhoto', $payload);
    }


    /**
     * Отправка фото с кнопками (Inline Keyboard)
     */
    public function sendPhotoWithButtons(string $chatId, $photo, string $caption, array $buttons): bool
    {
        // Структура кнопок для Telegram: массив строк или массив массивов кнопок
        // Пример $buttons: [
        //   ['text' => 'Играть', 'url' => 'https://site.com'],
        //   ['text' => 'Правила', 'callback_data' => 'rules']
        // ]

        $keyboard = $this->buildInlineKeyboard($buttons);

        return $this->sendPhoto(
            $chatId,
            $photo,
            $caption,
            ['reply_markup' => json_encode(['inline_keyboard' => $keyboard])]
        );
    }

    /**
     * Вспомогательный метод для построения клавиатуры
     */
    private function buildInlineKeyboard(array $buttons): array
    {
        $rows = [];
        foreach ($buttons as $row) {
            if (!is_array($row)) {
                continue;
            }
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Базовый запрос к API (JSON)
     */
    private function request(string $method, array $data): bool
    {
        try {
            $response = Http::asForm()->post("{$this->apiUrl}/{$method}", $data);

            if ($response->successful()) {
                return true;
            }

            Log::error('Telegram API Error', [
                'method' => $method,
                'data' => $data,
                'response' => $response->body()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Telegram Connection Error', ['message' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Запрос с файлом (multipart/form-data)
     */
    private function multipartRequest(string $method, array $data): bool
    {
        try {
            // Http фасад Laravel автоматически определит multipart, если есть ресурс (fopen)
            $response = Http::attach('photo', $data['photo'])->post(
                "{$this->apiUrl}/{$method}",
                [
                    'chat_id' => $data['chat_id'],
                    'caption' => $data['caption'] ?? '',
                ]
            );

            if ($response->successful()) {
                return true;
            }

            Log::error('Telegram File Upload Error', [
                'method' => $method,
                'response' => $response->body()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Telegram File Connection Error', ['message' => $e->getMessage()]);
            return false;
        }
    }




    public function sendRoles($account)
    {
        $chatId = $account->telegram_id;

        // Правильная проверка на пустой/невалидный chat_id
        if (empty($chatId)) {
            return false;
        }

        $photoMap = [
            'Шериф'      => 'she.png',
            'Адвокат'     => 'adv.png',
            'Бомба'       => 'bom.png',
            'Каратель'    => 'fer.png',
            'Детектив'    => 'det.png',
            'Доктор'      => 'doc.png',
            'Стрелочник'  => 'str.png',
            'Судья'       => 'sud.png',
            'Мафия'       => 'maf.png',
            'Мирный'      => 'mir.png',
            'Журналист'   => 'jur.png',
            'Дон'         => 'don.png',
            'Приспешник'  => 'pri.png',
            'Оборотень'  => 'obo.png',
            'Фредди'      => 'mst.png',
        ];


        $photo = env('APP_URL') . '/rols-b/' . $photoMap[$account->role] . '?v=' . time();

        // Вариант 2 (если картинки в storage/app/public/rols — раскомментируй вместо asset()):
        // $photo = Storage::disk('public')->url('rols/' . $imageName);

        $role = $account->role;
        if ($role == 'Фредди') {
            $role = 'Мститель';
        }
        if ($role == 'Каратель') {
            $role = 'Ферзь';
        }

        $caption = 'Ваша роль: ' . $role;
        $buttons = [
            [
                [
                    'text' => '📜 Правила роли',
                    // Вместо 'url' пишем 'web_app' и передаем прямую ссылку
                    'web_app' => ['url' => env('APP_URL') . '?tab=roles&id=' . $account->telegram_id]
                ]
            ],
            [
                [
                    'text' => '💬 Чат клуба',
                    'url' => 'https://t.me/lismafia_chat'
                ]
            ]
        ];

        $keyboard = $this->buildInlineKeyboard($buttons);

        $response = $this->sendPhoto(
            $chatId,
            $photo,
            $caption,
            ['reply_markup' => json_encode(['inline_keyboard' => $keyboard])]
        );
    }

    public function sendRolesViaEmail($account)
    {
        $email = $account->email;

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        try {
            // Просто передаем email получателя и созданный класс mailable
            Mail::to($email)->send(new GameRoleMail($account));
            return true;
        } catch (\Exception $e) {
            Log::error('Ошибка при отправке GameRoleMail через SMTP: ' . $e->getMessage());
            return false;
        }
    }


    public function sendResults($results)
    {


        if ($results->isEmpty()) {
            Log::info('TelegramService::sendResults: результатов нет, отправка пропущена');
            return false;
        }

        // Сортируем по рейтингу (по убыванию) — предполагаем, что рейтинг хранится в $result->rating
        $sorted = $results->sortByDesc(fn($r) => $r->total ?? 0);

        $lines = [];
        $lines[] = '🏆 Результаты игры';
        $lines[] = '';

        foreach ($sorted as $index => $result) {
            $account = $result->account ?? null;
            $place = $index + 1;
            $nickname = $account->nickname;
            $role = $account->role ?? '—';
            $rating = $result->total ?? 0;

            // Формируем строку с местом, ником, ролью, очками
            $lines[] = sprintf(
                '%d. %s (%s) — %d очков',
                $place,
                $nickname,
                $role,
                $rating
            );
        }

        $lines[] = '';
        $winner = $sorted->first();
        $winnerAccount = $winner->account ?? null;
        $winnerName = $winnerAccount->nickname ?? $winnerAccount->name ?? 'Неизвестный';
        $lines[] = '🥇 Победитель: ' . $winnerName;

        $text = implode(PHP_EOL, $lines);

        // Отправляем сообщение всем участникам (по их telegram_id)
        $successCount = 0;
        $accounts = collect([]);
        foreach ($results as $result) {

            $account = $result->account ?? null;
            $accounts->push($account);
            if (!$account || empty($account->telegram_id)) {
                continue; // нет telegram_id — пропускаем
            }

            $this->sendMessage($account->telegram_id, $text);

            $successCount++;
        }

        // $service = new MafiaImageService();
        // $imageFilename = $service->generateLayoutImage($accounts);

        Log::info('TelegramService::sendResults: отчёт отправлен', [
            'total_results' => $results->count(),
            'sent_count' => $successCount,
            'not_sent_count' => $results->count() - $successCount,
        ]);

        return $successCount > 0;
    }


    /**
     * Отправка результатов игры с учётом победителей (GameWinner)
     *
     * @param \Illuminate\Support\Collection $winnersCollection Коллекция объектов GameWinner
     * @param int $gameId ID игры (для логирования и возможного использования)
     * @return array ['sent' => int, 'failed' => int]
     */




    public function sendAch($ach, $telegram_id)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');

        // Динамический текст на основе переданного достижения $ach
        // Обычный текст, перенос строки \n и текст курсивом внутри тегов <i>...</i>
        $caption = "Вы получили новое достижение: <b>{$ach->text}</b>!\n\n<i>{$ach->description}</i>";

        // Отправляем обычный POST-запрос, просто передавая URL картинки в поле 'photo'
        $response = Http::post("https://api.telegram.org/bot{$botToken}/sendPhoto", [
            'chat_id'    => $telegram_id, // Используем переданный ID пользователя вместо статичного
            'photo'      => env('APP_URL') . '/medal.png', // Ссылка на картинку
            'parse_mode' => 'HTML',
            'caption'    => $caption
        ]);

        // Возвращаем результат (true в случае успеха, false при ошибке)
        return $response->successful();
    }
}
