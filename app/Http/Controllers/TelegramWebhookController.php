<?php

namespace App\Http\Controllers;

use App\Http\Helpers\TgHelper;
use App\Models\Account;
use App\Models\Admin;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TelegramWebhookController extends Controller
{
    protected string $token = '8338210523:AAGZ_njZqRpbwVl8RoOoK3Dt5g1sQKLrovI';

    public function webhook(Request $request)
    {
        $update = $request->all();
        file_put_contents('telegram_webhook.log', json_encode($update) . PHP_EOL, FILE_APPEND);

        if (isset($update['my_chat_member'])) {
            return response()->json(['status' => 'ok', 'type' => 'my_chat_member'], 200);
        }

        $chatId = null;
        $firstName = 'Игрок';
        $username = null;

        $payload = null;
        $isCallback = false;
        $messageId = null;

        if (isset($update['message'])) {
            $telegram_id = $update['message']['from']['id'];
            $this->sendKeyboard($update['message']['chat']['id'], $telegram_id);
            $msg = $update['message'];
            $from = $msg['from'] ?? [];
            $chatId = $msg['chat']['id'] ?? null;
            $firstName = $from['first_name'] ?? 'Игрок';
            $username = $from['username'] ?? null;
            $text = $msg['text'] ?? '';

            // Обновить telegram_name если поле не заполнено
            $this->fillTelegramNameIfMissing($telegram_id, $firstName);

            if ($text === '/start') {
                $this->showStartMenu($chatId, $firstName);
                return response()->json(['status' => 'menu_shown'], 200);
            }

            // Команда /menu — чтобы тестировать главное меню
            if ($text === '/menu') {
                $this->showMainMenu($chatId, $firstName);
                return response()->json(['status' => 'main_menu_shown'], 200);
            }

            if (preg_match('/^\/start\s+(\d+)$/i', $text, $matches)) {
                $siteUserId = (int)$matches[1];
                $result = $this->linkExistingAccount($siteUserId, $chatId, $username, $firstName, $telegram_id);
                return response()->json($result);
            }
        } elseif (isset($update['callback_query'])) {
            $cb = $update['callback_query'];
            $from = $cb['from'] ?? [];
            $chatId = $cb['message']['chat']['id'] ?? null;
            $messageId = $cb['message']['message_id'] ?? null;
            $payload = $cb['data'] ?? '';
            $isCallback = true;
            $firstName = $from['first_name'] ?? 'Игрок';
            $username = $from['username'] ?? null;
            $telegram_id = $cb['from']['id'] ?? null;

            // Обновить telegram_name если поле не заполнено
            if ($telegram_id) {
                $this->fillTelegramNameIfMissing($telegram_id, $firstName);
            }
        }

        if (!$chatId) {
            return response()->json(['status' => 'no_chat_id'], 400);
        }

        // Обрабатываем callback-кнопки (если они есть)
        if ($isCallback && $payload) {
            switch ($payload) {
                case 'link_existing':
                    $this->handleLinkExisting($chatId, $username, $firstName, $messageId, $telegram_id);
                    break;
                case 'create_new':
                    file_put_contents('id.txt', (string)$telegram_id . PHP_EOL, FILE_APPEND | LOCK_EX);
                    $t_id = (string)$telegram_id;
                    $this->handleCreateNew($chatId, $username, $firstName, $messageId, $telegram_id, $t_id);
                    break;
                case 'back_to_menu':
                    $this->showMainMenu($chatId, $firstName);
                    break;
                default:
                    if (str_starts_with($payload, 'select_club_')) {
                        $clubId = (int)str_replace('select_club_', '', $payload);
                        $this->handleSelectClub($chatId, $firstName, $messageId, $telegram_id, $clubId);
                    }
                    break;
            }
            return response()->json(['status' => 'callback_processed'], 200);
        }

        return response()->json(['status' => 'ok', 'processed' => false]);
    }

    /**
     * Заполняет telegram_name у существующего аккаунта, если поле пустое.
     */
    private function fillTelegramNameIfMissing($telegram_id, string $firstName): void
    {
        $account = Account::where('telegram_id', (string)$telegram_id)->first();
        if ($account && empty($account->telegram_name)) {
            $account->update(['telegram_name' => $firstName]);
        }
    }

    private function showStartMenu(int $chatId, string $firstName): void
    {
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '✅ Да, у меня есть аккаунт', 'callback_data' => 'link_existing'],
                ],
                [
                    ['text' => '🆕 Нет, хочу создать новый', 'callback_data' => 'create_new'],
                ]
            ]
        ];

        $text = "👋 Привет, {$firstName}!\n\nУ вас уже есть аккаунт в нашей Федерации?";
        $this->sendMessage($chatId, $text, $keyboard);
    }

    /**
     * Главное меню: кнопки-ссылки (url)
     */
    private function showMainMenu(int $chatId, string $firstName): void
    {
        $baseUrl = 'https://kashevsky.by'; // замени на свой домен, если надо

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '↩ Вернуться в начало', 'callback_data' => 'back_to_menu']
                ]
            ]
        ];

        $text = "👋 Добро пожаловать, {$firstName}!";
        $this->sendMessage($chatId, $text, $keyboard);
        $this->sendKeyboard($chatId, $chatId);
    }

    public function sendKeyboard($chatId, $telegram_id)
    {
        $baseUrl = 'https://foksfed.com';
        $t_id = (string)$telegram_id;
        $account = Account::where('telegram_id', $t_id)->first();
        if (!$account) {
            return;
        }
        $version = rand(1, 9999);

        if ($account->club_id == null) {
            $keyboard = [
                'keyboard' => [
                    [
                        [
                            'text' => '🏠 Главная',
                            'web_app' => ['url' => $baseUrl . "/?id={$t_id}&v={$version}"]
                        ],
                        [
                            'text' => '👤 Мой профиль',
                            'web_app' => ['url' => $baseUrl . "/account?id={$t_id}&v={$version}"]
                        ],
                    ],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'persistent' => true,
            ];
        } else {
            $keyboard = [
                'keyboard' => [
                    [
                        [
                            'text' => '🏠 Главная',
                            'web_app' => ['url' => $baseUrl . "/?id={$t_id}&v={$version}"]
                        ],
                        [
                            'text' => '📅 События',
                            'web_app' => ['url' => $baseUrl . "/events?id={$t_id}&v={$version}"]
                        ],
                        [
                            'text' => '🛍 Магазин',
                            'web_app' => ['url' => $baseUrl . "/store?id={$t_id}&v={$version}"]
                        ],
                    ],
                    [
                        [
                            'text' => '👤 Мой профиль',
                            'web_app' => ['url' => $baseUrl . "/account?id={$t_id}&v={$version}"]
                        ],
                    ]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'persistent' => true,
            ];
        }


        $data = [
            'chat_id'      => $chatId,
            'text'         => '📌 Меню всегда доступно внизу экрана:',
            'reply_markup' => json_encode($keyboard, JSON_UNESCAPED_UNICODE),
        ];

        return Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", $data);
    }


    private function linkExistingAccount(int $siteUserId, int $chatId, ?string $username, string $firstName, $telegram_id)
    {
        $account = Account::where('id', $siteUserId)->first();
        if (!$account) {
            $this->sendMessage($chatId, "❌ Ошибка: аккаунт с ID {$siteUserId} не найден.");
            return ['status' => 'link_failed', 'reason' => 'account_not_found'];
        }

        $existingAccount = Account::where('telegram_id', $telegram_id)->first();
        if ($existingAccount && $existingAccount->id !== $account->id) {
            $this->sendMessage(
                $chatId,
                "❌ Этот Telegram уже привязан к другому аккаунту.\nОбратите в поддержку."
            );
            return ['status' => 'link_failed', 'reason' => 'telegram_already_used'];
        }

        $account->update([
            'telegram_id'            => $telegram_id,
            'telegram_username'      => $username,
            'telegram_name'          => $firstName,
            'is_telegram_registered' => true,
        ]);

        $this->showMainMenu($chatId, $firstName);
        return ['status' => 'linked', 'account_id' => $account->id];
    }

    private function handleLinkExisting(int $chatId, ?string $username, string $firstName, ?int $messageId, $telegram_id): void
    {
        $t_id = (string)$telegram_id;
        $existing = Account::where('telegram_id', $t_id)->first();
        if ($existing) {
            $this->editMessageText(
                $chatId,
                $messageId,
                "👤 Ваш Telegram уже привязан к аккаунту #{$existing->id}.\nЕсли это ошибка — обратитесь в поддержку."
            );
            return;
        }

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '🆕 Нет, хочу создать новый', 'callback_data' => 'create_new'],
                ],
                [
                    ['text' => '↩ Вернуться в меню', 'callback_data' => 'back_to_menu'],
                ]
            ]
        ];

        $text = "🔗 Чтобы привязать аккаунт, зайдите в Федерацию в свой профиль и нажмите кнопку «Привязать Telegram».\n\nИли выберите один из вариантов ниже:";

        try {
            Http::asForm()
                ->post("https://api.telegram.org/bot{$this->token}/editMessageText", [
                    'chat_id'     => $chatId,
                    'message_id'  => $messageId,
                    'text'        => $text,
                    'parse_mode'  => 'HTML',
                    'reply_markup' => json_encode($keyboard, JSON_UNESCAPED_UNICODE),
                ])
                ->throw(false);
        } catch (\Exception $e) {
            Log::error('Error editing message', ['error' => $e->getMessage()]);
        }
    }

    private function handleCreateNew($chatId, $username, $firstName, $messageId, $telegram_id, $t_id)
    {
        // 1. Проверяем, нет ли уже существующего аккаунта
        $existing = Account::where('telegram_id', $t_id)->first();
        if ($existing) {
            $this->editMessageText(
                $chatId,
                $messageId,
                "👤 У вас уже есть аккаунт (#{$existing->id}), привязанный к этому Telegram."
            );
            return;
        }

        // 2. Создаем запись нового аккаунта в базе данных
        Account::create([
            'telegram_id'            => (string)$t_id,
            'telegram_username'      => $username,
            'telegram_name'          => $firstName,
            'name'                   => $firstName,
            'is_telegram_registered' => true,
            'club_id'                => null, // Клуб пользователь выберет на следующем шаге
        ]);

        // 3. Запись в лог/файл
        file_put_contents('id.txt', (string)$telegram_id . PHP_EOL, FILE_APPEND | LOCK_EX);

        // 4. Оповещение администраторов о новой регистрации
        $admins = Admin::get();
        $this->newSender('-1004385546556', $t_id, $firstName);
        // foreach ($admins as $admin) {
        //     if (!empty($admin->telegram_id)) {
        //         try {
        //             // Передаем реальный telegram_id админа
        //             $this->newSender($admin->telegram_id, $t_id, $firstName);
        //         } catch (\Exception $e) {
        //             Log::error("Failed to notify admin {$admin->id}", ['error' => $e->getMessage()]);
        //         }
        //     }
        // }

        // 5. Переводим пользователя на экран выбора клуба
        $this->showClubSelection($chatId, $messageId);
    }

private function newSender($admin_id, $t_id, $firstName)
{
    $bot_token = env('TELEGRAM_BOT_TOKEN');
    $telegram_id = $admin_id;

    // Экранируем имя, чтобы символы вроде '<' или '&' в нике пользователя не ломали HTML-разметку Telegram
    $safeFirstName = htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8');

    $text = '🆕 Зарегистрировался новый пользователь через Telegram!' . PHP_EOL;
    $text .= 'Имя: ' . $safeFirstName . PHP_EOL;
    $text .= "Ссылка: <a href=\"tg://user?id=" . $t_id . "\">Перейти к диалогу</a>";

    $params = [
        'chat_id' => $telegram_id,
        'text' => $text,
        'parse_mode' => 'HTML',
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.telegram.org/bot' . trim($bot_token) . '/sendMessage',
        CURLOPT_POST => true,
        CURLOPT_TIMEOUT => 10,
        // ИСПРАВЛЕНО: Передаем данные в чистом JSON-формате
        CURLOPT_POSTFIELDS => json_encode($params),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        // ИСПРАВЛЕНО: Явно указываем заголовок JSON, чтобы Telegram корректно распарсил HTML-теги
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ]
    ]);

    $result = curl_exec($curl);
    curl_close($curl);

    return $result;
}






    private function showClubSelection(int $chatId, ?int $messageId): void
    {
        $clubs = Club::orderBy('title')->get();

        if ($clubs->isEmpty()) {
            $this->sendMessage($chatId, "⚠️ Клубы не найдены. Обратитесь в поддержку.");
            return;
        }

        $rows = [];
        foreach ($clubs as $club) {
            $rows[] = [['text' => $club->title, 'callback_data' => 'select_club_' . $club->id]];
        }

        $keyboard = ['inline_keyboard' => $rows];
        $text = "🏟 Выберите ваш клуб:";

        if ($messageId) {
            try {
                Http::asForm()
                    ->post("https://api.telegram.org/bot{$this->token}/editMessageText", [
                        'chat_id'      => $chatId,
                        'message_id'   => $messageId,
                        'text'         => $text,
                        'parse_mode'   => 'HTML',
                        'reply_markup' => json_encode($keyboard, JSON_UNESCAPED_UNICODE),
                    ])
                    ->throw(false);
            } catch (\Exception $e) {
                Log::error('Error showing club selection', ['error' => $e->getMessage()]);
            }
        } else {
            $this->sendMessage($chatId, $text, $keyboard);
        }
    }

    private function handleSelectClub(int $chatId, string $firstName, ?int $messageId, $telegram_id, int $clubId): void
    {
        $account = Account::where('telegram_id', (string)$telegram_id)->first();
        if (!$account) {
            $this->sendMessage($chatId, "❌ Аккаунт не найден. Попробуйте заново.");
            return;
        }

        $club = Club::find($clubId);
        if (!$club) {
            $this->sendMessage($chatId, "❌ Клуб не найден. Попробуйте ещё раз.");
            $this->showClubSelection($chatId, $messageId);
            return;
        }

        $account->update(['club_id' => $clubId]);

        if ($messageId) {
            $this->editMessageText($chatId, $messageId, "✅ Клуб <b>{$club->name}</b> выбран!");
        }

        $this->showMainMenu($chatId, $firstName);
    }

    private function sendMessage(int $chatId, string $text, ?array $keyboard = null): void
    {
        if (empty($this->token) || empty($chatId)) {
            Log::error('sendMessage aborted: missing token or chat_id');
            return;
        }

        try {
            $data = [
                'chat_id'    => $chatId,
                'text'       => $text,
                'parse_mode' => 'HTML',
            ];

            if ($keyboard) {
                $jsonKeyboard = json_encode($keyboard, JSON_UNESCAPED_UNICODE);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Invalid keyboard JSON structure!', [
                        'error' => json_last_error_msg(),
                        'keyboard_raw' => print_r($keyboard, true)
                    ]);
                    return;
                }
                $data['reply_markup'] = $jsonKeyboard;
            }

            Log::debug('Sending message to Telegram', ['chat_id' => $chatId, 'has_keyboard' => (bool)$keyboard]);

            $response = Http::asForm()
                ->post("https://api.telegram.org/bot{$this->token}/sendMessage", $data);

            $body = $response->body();
            $decoded = json_decode($body, true);

            if (!$response->ok() || !($decoded['ok'] ?? false)) {
                Log::error('Telegram API returned error', [
                    'status_code' => $response->status(),
                    'response' => $body
                ]);
            } else {
                Log::info('Message sent successfully (buttons should appear)', ['chat_id' => $chatId]);
            }
        } catch (\Exception $e) {
            Log::critical('Exception in sendMessage', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    private function editMessageText(int $chatId, ?int $messageId, string $text): void
    {
        if (empty($this->token) || empty($chatId) || !$messageId) {
            return;
        }

        try {
            Http::asForm()
                ->post("https://api.telegram.org/bot{$this->token}/editMessageText", [
                    'chat_id'    => $chatId,
                    'message_id' => $messageId,
                    'text'       => $text,
                    'parse_mode' => 'HTML',
                ])
                ->throw(false);
        } catch (\Exception $e) {
            Log::error('Error editing message text', ['error' => $e->getMessage()]);
        }
    }
}
