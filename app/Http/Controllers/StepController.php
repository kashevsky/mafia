<?php

namespace App\Http\Controllers;


use App\Http\Services\AchivmentService;
use App\Http\Services\DayFinalService;
use App\Http\Services\DayRatingService;
use App\Http\Services\FinishService;
use App\Http\Services\NightFinalService;
use App\Http\Services\NightRatingService;
use App\Models\Account;
use App\Models\Table;
use Illuminate\Http\Request;

class StepController extends Controller
{
    public function log(Request $request)
    {
        $params = $request->all();

        if (empty($params['users'])) {
            return response()->json(['error' => 'No users data'], 400);
        }

        $table_id = $params['users'][0]['table_id'];
        $table = Table::find($table_id);
        $step = $table->step;

        // === КРИТИЧЕСКИЙ ТОЧКА: ФИНАЛ ИГРЫ ===
        // Если пришел флаг завершения игры, обрабатываем ТОЛЬКО его и сразу выходим
        if (isset($params['finishGame'])) {
            if($params['finishGame'] == 'мафия') {
                $table->is_mafia_win = 1;
            } else {
                $table->is_mafia_win = 0;
            }
            $table->step = $table->step + 1;
            $table->save();
            new FinishService($params['users'], $params['finishGame'], $step);
            return response()->json(['status' => 'game_finished']);
        }

        // === СТАНДАРТНАЯ ЛОГИКА ДЛЯ ИГРОВЫХ ХОДОВ ===
        if (isset($params['fromUser']['id']) && isset($params['toUser']['id'])) {
            $from_user_id = $params['fromUser']['id'];
            $to_user_id = $params['toUser']['id'];
            $from_user = Account::find($from_user_id);
            $to_user = Account::find($to_user_id);

            // Сервис ачивок за конкретные ходы
            new AchivmentService($from_user, $to_user, $params, $step);
        }

        $stage = $params['stageGameObj']['text'] ?? '';

        if ($stage == 'результат голосования (день)') {
            new DayFinalService($params, $step);
        }

        if ($stage == 'конец игры (ночь)') {
            $step = Table::find($table_id)->step;
            new NightFinalService($params, $step);
            return response()->json(['status' => 'night_final_processed']);
        }

        $what_day = $params['whatDay'] ?? '';

        // Защита: если пользователи хода не определены, обычные рейтинги за ходы не считаем
        if (!isset($from_user) || !isset($to_user)) {
            return response()->json(['status' => 'step_logged_without_rating']);
        }

        // Начисление рейтингов за текущие действия (день/ночь)
        if ($what_day != 'День' && $stage != 'конец игры (ночь)') {
            file_put_contents('rating-log.txt', '--' . "\n", FILE_APPEND);
            new NightRatingService($from_user, $to_user, $params, $step);
        } elseif ($what_day == 'День') {
            new DayRatingService($from_user, $to_user, $step);
        }

        return response()->json(['status' => 'success']);
    }
}
