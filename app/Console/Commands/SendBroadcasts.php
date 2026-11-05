<?php

namespace App\Console\Commands;

use App\Http\Helpers\TgHelper;
use App\Http\Services\TelegramService;
use App\Models\Account;
use App\Models\Achivment;
use App\Models\Application;
use App\Models\Broadcast;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendBroadcasts extends Command
{
    protected $signature = 'broadcasts:send';
    protected $description = 'Отправляем рассылки пользователям в Telegram (с учётом запланированного времени)';



    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {



        $this->info('Запуск массовой рассылки в Telegram...');

        // Абсолютный путь к файлу гарантирует запись при работе Cron
        file_put_contents(public_path('log-broadcast.txt'), '--' . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);


        // Получаем рассылки, которые:
        // 1. Имеют scheduled_time <= текущего времени
        // 2. Ещё не отправлены (статус не 'sent')
        // 3. Не находятся в процессе отправки (статус не 'sending')
        $currentDateTime = Carbon::now('Europe/Moscow');
        $broadcasts = Broadcast::where('scheduled_time', '<=', $currentDateTime)
            ->whereNotIn('status', ['sent', 'sending'])
            ->get();




        if ($broadcasts->isEmpty()) {
            $this->warn('Нет рассылок для отправки в данный момент.');
            return Command::SUCCESS;
        }

        $totalSent = 0;
        $totalErrors = 0;

        foreach ($broadcasts as $broadcast) {
            $this->info("Обработка рассылки ID: {$broadcast->id}, запланированное время: {$broadcast->scheduled_time}");

            // Получаем ID получателей из JSON-поля
            $recipients = json_decode($broadcast->recipients, true);

            if (empty($recipients)) {
                $this->warn("В рассылке ID {$broadcast->id} нет получателей.");
                // Помечаем рассылку как завершённую с ошибкой
                $broadcast->update([
                    'status' => 'error',
                    'error_count' => 1,
                    'last_sent_at' => now(),
                ]);
                continue;
            }

            // Получаем аккаунты с заполненным Telegram ID
            $accounts = Account::whereIn('id', $recipients)
                ->whereNotNull('telegram_id')
                ->get();

            if ($accounts->isEmpty()) {
                $this->warn("Для рассылки ID {$broadcast->id} не найдено аккаунтов с Telegram ID.");
                // Помечаем рассылку как завершённую с ошибкой
                $broadcast->update([
                    'status' => 'error',
                    'error_count' => 1,
                    'last_sent_at' => now(),
                ]);
                continue;
            }

            $this->info("Найдены {$accounts->count()} аккаунтов для рассылки.");

            // Обновляем статус рассылки — начинаем отправку
            $broadcast->update(['status' => 'sending']);

            $sentCount = 0;
            $errorCount = 0;
            $accounts = $accounts->unique('telegram_id');

            foreach ($accounts as $account) {
                try {
                    // Отправляем сообщение через хелпер
                    $success = TgHelper::sendBroadcast($broadcast);

                    if ($success) {
                        $sentCount++;
                        $totalSent++;
                    } else {
                        $errorCount++;
                        $totalErrors++;
                        Log::error("Ошибка отправки сообщения пользователю ID: {$account->id}", [
                            'broadcast_id' => $broadcast->id,
                            'account_id' => $account->id
                        ]);
                    }

                    // Задержка для соблюдения лимитов Telegram API (1 сообщение в секунду)
                    sleep(1);
                } catch (\Exception $e) {
                    $errorCount++;
                    $totalErrors++;
                    Log::error("Исключение при отправке сообщения пользователю ID: {$account->id}", [
                        'broadcast_id' => $broadcast->id,
                        'exception' => $e->getMessage()
                    ]);
                }
            }

            // Обновляем статус и статистику рассылки
            $broadcast->update([
                'status' => $errorCount > 0 ? 'partially_sent' : 'sent',
                'sent_count' => $sentCount,
                'error_count' => $errorCount,
                'last_sent_at' => now(),
            ]);

            $this->info("Рассылка ID {$broadcast->id}: отправлено {$sentCount}, ошибок: {$errorCount}");
        }

        $this->info("Массовая рассылка завершена. Всего отправлено: {$totalSent}, ошибок: {$totalErrors}");

        return Command::SUCCESS;
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
}
