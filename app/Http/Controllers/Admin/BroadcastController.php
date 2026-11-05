<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AuthHelper;
use App\Http\Helpers\TgHelper;
use App\Models\Account;
use App\Models\Broadcast;
use App\Models\BroadcastUser;
use App\Models\City;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class BroadcastController extends Controller
{

    public function index()
    {
        $broadcasts = Broadcast::with('accounts')->get();
        $users = Account::with('club')->get();
        $clubs = Club::get();
        $cities = City::get();
        $admin = AuthHelper::getAdmin();
        $broadcast = Broadcast::with('accounts')->get()->last();

        return view('admin.broadcast', compact('broadcasts', 'users', 'clubs', 'cities', 'admin'));
    }
    public function testSend(Request $request)
    {
        $params = $request->all();

        // Получаем данные из запроса
        $message = $params['message'] ?? '';
        $buttonText = $params['button_text'] ?? null;
        $buttonUrl = $params['button_url'] ?? null;



        // Настройки Telegram API
        $botToken = '8338210523:AAGZ_njZqRpbwVl8RoOoK3Dt5g1sQKLrovI'; // Токен бота из .env
        $chatId = '5325830700'; // ID чата для тестовых сообщений из .env




        $apiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";

        // Подготавливаем клавиатуру с кнопкой, если заданы параметры кнопки
        $replyMarkup = null;
        if ($buttonText && $buttonUrl) {
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

        // Формируем данные для отправки
        $postData = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

        // Добавляем клавиатуру, если она создана
        if ($replyMarkup) {
            $postData['reply_markup'] = $replyMarkup;
        }

        // Обрабатываем изображения, если они есть
        $images = $request->file('images', []);
        $hasImages = !empty($images);

        if ($hasImages) {
            // Если есть изображения, отправляем сначала их
            $photoApiUrl = "https://api.telegram.org/bot{$botToken}/sendPhoto";

            foreach ($images as $image) {
                if ($image->isValid()) {
                    // Создаём cURL для отправки файла
                    $ch = curl_init();

                    // Подготавливаем файл для отправки
                    $curlFile = curl_file_create(
                        $image->getPathname(),
                        $image->getClientMimeType(),
                        $image->getClientOriginalName()
                    );

                    $photoData = [
                        'chat_id' => $chatId,
                        'photo' => $curlFile
                    ];

                    curl_setopt_array($ch, [
                        CURLOPT_URL => $photoApiUrl,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $photoData,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_SSL_VERIFYPEER => false
                    ]);

                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                }
            }

            // После отправки изображений отправляем текстовое сообщение с кнопкой
            // (если сообщение не пустое или есть кнопка)
            if (!empty($message) || $buttonText) {
                $this->sendTelegramMessage($apiUrl, $postData);
            }
        } else {
            // Если изображений нет — просто отправляем сообщение
            $result = $this->sendTelegramMessage($apiUrl, $postData);

            if (!$result['success']) {
                return response()->json($result, 500);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Тестовая рассылка отправлена успешно'
        ]);
    }

    public function send(Request $request)
    {
        $params = $request->all();


        $broadcast = Broadcast::find($params['id']);
        TgHelper::sendBroadcast($broadcast);
    }

    private function sendTelegramMessage(string $apiUrl, array $postData): array
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false // Для самоподписанных сертификатов
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        if ($httpCode !== 200 || !isset($decodedResponse['ok']) || !$decodedResponse['ok']) {
            return [
                'success' => false,
                'message' => 'Ошибка Telegram API',
                'details' => $decodedResponse ?? $response
            ];
        }

        return ['success' => true];
    }

    public function delete($id)
    {
        $broadcast = Broadcast::find($id);
        $broadcast->delete();
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $params = $request->all();



        $broadcast = Broadcast::create([
            'message' => $params['message'],
        ]);

        if (isset($params['button_text'])) {
            $broadcast->button_text = $params['button_text'];
        }

        if (isset($params['button_url'])) {
            $broadcast->button_url = $params['button_url'];
        }

        if (isset($params['scheduled_time'])) {
            $broadcast->scheduled_time = $params['scheduled_time'];
        }

        if (isset($params['images']) && is_array($params['images'])) {
            $uploadPath = public_path('uploads/broadcasts');

            // Создаём директорию, если её нет
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($params['images'] as $index => $image) {
                // Проверяем, что это загруженный файл
                if ($image instanceof UploadedFile && $image->isValid()) {
                    // Генерируем уникальное имя файла, чтобы избежать конфликтов
                    $extension = $image->getClientOriginalExtension();
                    $filename = 'broadcast_' . $broadcast->id . '_' . $index . '_' . time() . '.' . $extension;

                    // Перемещаем файл
                    if ($image->move($uploadPath, $filename)) {
                        // Формируем имя свойства (src_1, src_2 и т. д.)
                        $propertyName = 'src_' . ($index + 1);
                        // Сохраняем путь в свойство модели
                        $broadcast->{$propertyName} = '/uploads/broadcasts/' . $filename;
                    } else {
                        // Логируем ошибку перемещения файла

                    }
                }
            }
        }
        if (isset($params['recipients'])) {
            $recipients = $params['recipients'];
            $broadcast->recipients = json_encode($recipients);

            foreach ($recipients as $recipient) {
                $account = Account::find($recipient);
                if ($account) {
                    BroadcastUser::create([
                        'broadcast_id' => $broadcast->id,
                        'account_id' => $account->id
                    ]);
                }
            }
        }

        $broadcast->save();

        $broadcasts = Broadcast::all();

        return json_encode([
            'success' => true,
            'broadcasts' => $broadcasts
        ]);
    }
}
