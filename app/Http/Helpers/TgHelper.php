<?php

namespace App\Http\Helpers;

use App\Models\Account;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TgHelper
{
    /**
     * Массовая рассылка — автоматически выбирает метод отправки (с медиа или без)
     */
    public static function sendBroadcast($broadcast)
    {
        $message = $broadcast->message ?? '';
        $buttonText = $broadcast->button_text ?? null;
        $buttonUrl = $broadcast->button_url ?? null;


        $cleanedRecipients = trim($broadcast->recipients, '"');


        $recipients = json_decode($cleanedRecipients, true) ?? [];

        $recipients = Account::whereIn('id', $recipients)->get()->pluck('telegram_id')->toArray();

        // Подготавливаем клавиатуру с кнопкой (если есть)
        $replyMarkup = null;
        if ($buttonText && $buttonUrl) {
            // Валидация URL
            if (!filter_var($buttonUrl, FILTER_VALIDATE_URL)) {
                Log::warning('Invalid URL for button', [
                    'broadcast_id' => $broadcast->id,
                    'button_text' => $buttonText,
                    'invalid_url' => $buttonUrl
                ]);
            } else {
                $replyMarkup = json_encode([
                    'inline_keyboard' => [
                        [
                            [
                                'text' => $buttonText,
                                'url' => $buttonUrl
                            ]
                        ]
                    ]
                ]);
            }
        }

        // Собираем ссылки на изображения (исправлена ошибка с $I → $i)
        $images = [];
        for ($i = 1; $i <= 4; $i++) {
            $fieldName = 'src_' . $i;
            if (!empty($broadcast->{$fieldName})) {
                $images[] = $broadcast->{$fieldName};
            }
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($recipients as $chatId) {
            sleep(0.5);
            try {
                // Логируем отправляемые параметры
                Log::debug('Sending message with params', [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'reply_markup' => $replyMarkup,
                    'has_images' => !empty($images),
                    'image_count' => count($images)
                ]);

                // Определяем метод отправки в зависимости от количества изображений
                if (count($images) === 1) {
                    // Для одного изображения используем sendPhoto (поддерживает кнопки)
                    $result = self::sendSinglePhotoWithButton($chatId, $images[0], $message, $replyMarkup);
                } elseif (!empty($images)) {
                    // Для нескольких изображений: сначала медиагруппа, затем отдельное сообщение с кнопкой
                    $mediaResult = self::sendMediaGroup($chatId, $images);

                    if ($mediaResult['success']) {
                        // Отправляем отдельное сообщение с кнопкой и текстом
                        $buttonParams = [
                            'chat_id' => $chatId,
                            'text' => $message,
                            'parse_mode' => 'HTML'
                        ];

                        if ($replyMarkup) {
                            $buttonParams['reply_markup'] = $replyMarkup;
                        }

                        $result = self::sendTelegramMessage($buttonParams);
                    } else {
                        $result = $mediaResult; // Если медиа не отправилось, используем его результат
                    }
                } else {
                    // Только текст с кнопкой
                    $params = [
                        'chat_id' => $chatId,
                        'text' => $message,
                        'parse_mode' => 'HTML'
                    ];

                    if ($replyMarkup) {
                        $params['reply_markup'] = $replyMarkup;
                    }

                    $result = self::sendTelegramMessage($params);
                }

                if ($result['success']) {
                    $successCount++;
                } else {
                    $errorCount++;
                    Log::error('Failed to send message to chat ID: ' . $chatId, [
                        'broadcast_id' => $broadcast->id,
                        'error' => $result['message']
                    ]);
                }

                // Задержка для соблюдения лимитов Telegram API
                sleep(1);
            } catch (\Exception $e) {
                $errorCount++;
                Log::error('Exception when sending to chat ID: ' . $chatId, [
                    'broadcast_id' => $broadcast->id,
                    'exception' => $e->getMessage()
                ]);
            }
        }

        Log::info('Broadcast completed', [
            'broadcast_id' => $broadcast->id,
            'total_recipients' => count($recipients),
            'success' => $successCount,
            'errors' => $errorCount
        ]);

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'total' => count($recipients)
        ];
    }

    /**
     * Отправка одного фото с текстом и кнопкой
     */
    private static function sendSinglePhotoWithButton($chatId, $imageUrl, $text = '', $replyMarkup = null): array
    {
        $bot_token = env('TELEGRAM_BOT_TOKEN');
        $apiUrl = 'https://api.telegram.org/bot' . $bot_token . '/sendPhoto';
        if (!str_contains($imageUrl, 'http://') && !str_contains($imageUrl, 'https://')) {
            $imageUrl = rtrim(env('APP_URL'), '/') . '/' . ltrim($imageUrl, '/');
        }



        $postData = [
            'chat_id' => $chatId,
            'photo' => $imageUrl,
            'caption' => $text,
            'parse_mode' => 'HTML'
        ];

        if ($replyMarkup) {
            $postData['reply_markup'] = $replyMarkup;
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        Log::info('Telegram API response', [

            'raw_response' => $response,
        ]);

        // Проверяем наличие ошибок cURL
        if ($error) {
            return [
                'success' => false,
                'message' => 'cURL error: ' . $error,
                'http_code' => $httpCode
            ];
        }

        $decodedResponse = json_decode($response, true);

        // Проверяем HTTP‑статус и ответ Telegram API
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'message' => 'HTTP error: ' . $httpCode,
                'details' => $decodedResponse ?? $response,
                'http_code' => $httpCode
            ];
        }

        if (!isset($decodedResponse['ok']) || !$decodedResponse['ok']) {
            return [
                'success' => false,
                'message' => 'Ошибка Telegram API при отправке фото с кнопкой',
                'details' => $decodedResponse ?? $response,
                'http_code' => $httpCode
            ];
        }

        return ['success' => true];
    }

    /**
     * Отправка медиагруппы (без кнопки)
     */
    private static function sendMediaGroup($chatId, $imageUrls): array
    {
        $media = [];
        $baseUrl = rtrim(env('APP_URL'), '/');

        foreach ($imageUrls as $url) {
            // Если url уже содержит протокол http:// или https://, оставляем как есть.
            // Иначе — склеиваем APP_URL и относительный путь.
            $absoluteUrl = (str_contains($url, 'http://') || str_contains($url, 'https://'))
                ? $url
                : $baseUrl . '/' . ltrim($url, '/');

            $media[] = [
                'type' => 'photo',
                'media' => $absoluteUrl
            ];
        }

        $params = [
            'chat_id' => $chatId,
            'media' => json_encode($media)
        ];

        try {
            $response = self::makeTelegramRequest('sendMediaGroup', $params);
            return ['success' => true, 'message' => $response];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    /**
     * Базовый метод для запросов к Telegram API
     */
    private static function makeTelegramRequest($method, $params)
    {
        $url = 'https://api.telegram.org/bot' . config('services.telegram.token') . '/' . $method;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false, // Отключаем проверку SSL для упрощения (не рекомендуется в продакшене)
            CURLOPT_HTTPHEADER => [
                'Content-Type: multipart/form-data'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        Log::info('Telegram API response', [
            'raw_response' => $response,
        ]);

        // Проверяем наличие ошибок cURL
        if ($error) {
            throw new \Exception("cURL error: " . $error);
        }

        // Проверяем HTTP‑статус
        if ($httpCode !== 200) {
            throw new \Exception("HTTP error: $httpCode, response: $response");
        }

        $result = json_decode($response, true);

        // Проверяем ответ Telegram API
        if (!isset($result['ok']) || !$result['ok']) {
            $errorMessage = $result['description'] ?? 'Unknown error';
            throw new \Exception("Telegram API error: $errorMessage");
        }

        return $result['result'];
    }

    /**
     * Вспомогательный метод для отправки текстовых сообщений
     */
    private static function sendTelegramMessage(array $postData): array
    {
        $bot_token = env('TELEGRAM_BOT_TOKEN');
        $apiUrl = 'https://api.telegram.org/bot' . $bot_token . '/sendMessage';

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        Log::info('Telegram API response', [

            'raw_response' => $response,
        ]);

        // Проверяем наличие ошибок cURL
        if ($error) {
            return [
                'success' => false,
                'message' => 'cURL error: ' . $error,
                'http_code' => $httpCode
            ];
        }

        $decodedResponse = json_decode($response, true);

        // Проверяем HTTP‑статус и ответ Telegram API
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'message' => 'HTTP error: ' . $httpCode,
                'details' => $decodedResponse ?? $response,
                'http_code' => $httpCode
            ];
        }

        if (!isset($decodedResponse['ok']) || !$decodedResponse['ok']) {
            return [
                'success' => false,
                'message' => 'Ошибка Telegram API',
                'details' => $decodedResponse ?? $response,
                'http_code' => $httpCode
            ];
        }

        return [
            'success' => true,
            'data' => $decodedResponse
        ];
    }

    /**
     * Отправка сообщения одному пользователю (опционально)
     */
    public static function sendMessage($text, $telegram_id)
    {
        $bot_token = env('TELEGRAM_BOT_TOKEN');

        $params = [
            'chat_id' => $telegram_id,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendMessage',
            CURLOPT_POST => true,
            CURLOPT_TIMEOUT => 10,
            // ИСПРАВЛЕНИЕ: Переводим массив в URL-строку, чтобы cURL отправил правильный Content-Type
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $result = curl_exec($curl);

        // Полезно для отладки: если что-то пойдет не так, вы увидите ошибку от самого Telegram в логах
        if ($result === false) {
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $httpCode === 200;
    }


    public static function sendMessageToCurrentUser($telegram_id, $text)
    {
        if (!$telegram_id) {
            $telegram_id = '5325830700';
        }
        $bot_token = env('TELEGRAM_BOT_TOKEN');
        $params = [
            'chat_id' => $telegram_id,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendMessage',
            CURLOPT_POST => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $result = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $httpCode === 200;
    }

    public static function sendPhotoWithCaption(string $telegramId, string $photoPath, string $caption = ''): bool
    {



        $botToken = env('TELEGRAM_BOT_TOKEN');



        $params = [
            'chat_id'   => $telegramId,
            'caption'   => $caption,
            'parse_mode' => 'HTML',
        ];

        // Для отправки локального файла используем CURLFile (работает в современных PHP)

        $params['photo'] =  $photoPath;
        $baseUrl = env('APP_URL') . '/';
        $photoPath = $photoPath; // Ваш исходный путь (например, 'images/photo.jpg' или 'https://foksfed.com')

        // Если путь НЕ начинается с http:// или https://, добавляем базовый URL
        if (!str_starts_with($photoPath, 'http://') && !str_starts_with($photoPath, 'https://')) {
            // Убираем лишний левый слэш у пути, если он есть, чтобы не получилось https://foksfed.com...
            $params['photo'] = $baseUrl . ltrim($photoPath, '/');
        } else {
            $params['photo'] = $photoPath;
        }


        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL             => 'https://api.telegram.org/bot' . $botToken . '/sendPhoto',
            CURLOPT_POST            => true,
            CURLOPT_TIMEOUT         => 10,
            CURLOPT_POSTFIELDS      => $params,          // важно: НЕ json_encode, это multipart/form-data
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYPEER  => false,           // в продакшене лучше включить и настроить CA
        ]);

        $result = curl_exec($curl);



        $err = curl_error($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        Log::info('Telegram API response', [
            'raw_response' => $result,
        ]);




        return $httpCode === 200;
    }



    public static function sendFinishRating(string $telegramId, string $photoPath, string $caption = ''): bool
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');


        $photoToTg = str_replace('screenshots/', '', $photoPath);

        $keyboard = [
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Скачать результаты',
                        'url'  => env('APP_URL') . '/save-results-finish/' . $photoToTg,
                    ]
                ]
            ]
        ];

        $params = [
            'chat_id'      => $telegramId,
            'caption'      => $caption,
            'parse_mode'   => 'HTML',
            'reply_markup' => json_encode($keyboard), // Обязательно кодируем в JSON
        ];

        $baseUrl = env('APP_URL') . '/';

        // Если путь НЕ начинается с http:// или https://, добавляем базовый URL
        if (!str_starts_with($photoPath, 'http://') && !str_starts_with($photoPath, 'https://')) {
            $params['photo'] = $baseUrl . ltrim($photoPath, '/');
        } else {
            $params['photo'] = $photoPath;
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL             => 'https://api.telegram.org/bot' . $botToken . '/sendPhoto',
            CURLOPT_POST            => true,
            CURLOPT_TIMEOUT         => 10,
            CURLOPT_POSTFIELDS      => $params,          // multipart/form-data принимает reply_markup в виде строки
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYPEER  => false,
        ]);

        $result = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        Log::info('Telegram API response', [
            'raw_response' => $result,
        ]);


        return $httpCode === 200;
    }


    public static function sendPhotoWithWebApp(
        string $telegramId,
        string $photoPath,
        string $caption,
        string $buttonText,
        string $webAppUrl,
        string $downloadUrl // Добавили URL для скачивания результатов
    ): bool {
        $botToken = env('TELEGRAM_BOT_TOKEN');


        // Формируем структуру клавиатуры с двумя кнопками (друг под другом)
        $replyMarkup = [
            'inline_keyboard' => [
                [
                    [
                        'text' => $buttonText,
                        'web_app' => ['url' => $webAppUrl]
                    ]
                ],
                [
                    [
                        'text' => 'Скачать результаты',
                        'url'  => $downloadUrl
                    ]
                ]
            ]
        ];


        $params = [
            'chat_id'      => $telegramId,
            'photo'        => $photoPath . '?v=' . time(),
            'caption'      => $caption,
            'parse_mode'   => 'HTML',
            'reply_markup' => json_encode($replyMarkup)
        ];



        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL             => 'https://api.telegram.org/bot' . $botToken . '/sendPhoto',
            CURLOPT_POST            => true,
            CURLOPT_TIMEOUT         => 10,
            CURLOPT_POSTFIELDS      => $params,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYPEER  => false,
        ]);

        $result = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);


        return $httpCode === 200;
    }


    public static function sendPhotoWithGroupWebApp(string $chatId, string $photoPath, string $caption, string $buttonText, string $resultUrl): bool
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $botName = 'foks_fed_bot';

        // Шаг 1. Формируем URL для группы
        if (preg_match('/temp-public\/(\d+)/', $resultUrl, $matches)) {
            $snapId = $matches[1];
            // Убедитесь, что 'app' — это актуальный Short Name вашего приложения в @BotFather
            $webAppGroupUrl = "https://t.me/{$botName}/app?startapp=results_{$snapId}";
        } else {
            $webAppGroupUrl = $resultUrl;
        }

        // Шаг 2. Формируем структуру кнопок
        $replyMarkup = [
            'inline_keyboard' => [
                [
                    [
                        'text' => $buttonText,
                        'url'  => $webAppGroupUrl // В группах работает ТОЛЬКО тип 'url'
                    ]
                ]
            ]
        ];

        // Шаг 3. Собираем чистый массив параметров
        $params = [
            'chat_id'      => $chatId,
            'photo'        => $photoPath, // Работает, если это прямая ссылка вида https://...
            'caption'      => $caption,
            'parse_mode'   => 'HTML',
            'reply_markup' => $replyMarkup // Оставляем массивом, json_encode сделаем для всего пакета сразу
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL             => 'https://api.telegram.org/bot' . $botToken . '/sendPhoto',
            CURLOPT_POST            => true,
            CURLOPT_TIMEOUT         => 10,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYPEER  => false,
            // Передаем данные как чистый JSON-пакет:
            CURLOPT_POSTFIELDS      => json_encode($params),
            CURLOPT_HTTPHEADER      => [
                'Content-Type: application/json'
            ],
        ]);

        $result = curl_exec($curl);

        // Временный дебаг: если не отправляется, раскомментируйте строку ниже, чтобы увидеть ошибку в логах Laravel
        // \Log::info('Telegram response: ' . $result);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $httpCode === 200;
    }
}
