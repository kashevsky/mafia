<?php

namespace App\Http\Services;

use App\Models\Account;
use App\Models\Achivment;
use App\Models\GameStep;
use App\Models\TempAchivment;
use App\Models\TempSaboter;
use Illuminate\Support\Facades\DB;



class DayFinalService
{
    public $game;
    public $users;
    public $killed_users;
    public $from_user;
    public $to_user;
    public $params;
    public $step;

    public function __construct($params, $step)
    {
        $this->params = $params;
        $this->users = $params['users'];
        $this->game = $params;
        $this->step = $step;
        $this->game['id'] = $this->users[0]['game_id'];
        $ids = array_column(array_column($params['resumeKillUsers'], 'user'), 'id');
        $this->killed_users = Account::whereIn('id', $ids)->get();
        $this->handle();
        $this->checkAchivments();
    }

    public function handle()
    {
        $game_steps = GameStep::where('is_confirmed', 0)
        ->where('game_id', $this->game['id'])
        ->get();

        foreach ($game_steps as $game_step) {

            $this->check($game_step);
            $game_step->is_confirmed = 1;
            $game_step->save();
        }

        $specialRoles = ['Шериф', 'Доктор', 'Каратель', 'Журналист', 'Стрелочник', 'Бомба'];
        // Выносим текст в переменную, чтобы не дублировать и не опечататься
        $targetText = 'Жертва протокола: был казненным в первый день, будучи важной активной ролью (Шериф, Доктор, Палач, Журналист, Стрелочник, Бомба), 5 раз.';

        // 1. Разделяем пользователей на две группы сразу в памяти (без запросов к БД)
        $specialUsers = $this->killed_users
            ->filter(fn($u) => in_array($u->role, $specialRoles, true))
            ->pluck('id')
            ->all();


        $nonSpecialUsers = $this->killed_users
            ->filter(fn($u) => !in_array($u->role, $specialRoles, true))
            ->pluck('id')
            ->all();

        if (empty($specialUsers) && empty($nonSpecialUsers)) {
            return;
        }

        DB::transaction(function () use ($specialUsers, $nonSpecialUsers, $targetText) {
            // 2. ОДИН запрос: получаем все GameStep для всех затронутых пользователей
            $gameSteps = GameStep::whereIn('to_user_id', array_merge($specialUsers, $nonSpecialUsers))
                ->where('text', $targetText)
                ->get();

            // Группируем шаги по account_id для быстрого доступа: $stepsByUser[$userId] = Collection
            $stepsByUser = $gameSteps->groupBy('to_user_id');

            $toCreateAchievement = [];
            $toCreateGameStep = [];

            // Обрабатываем только пользователей со спецролями
            foreach ($specialUsers as $userId) {

                // Получаем количество шагов из уже загруженной коллекции (0 запросов к БД!)
                $stepsForUser = $stepsByUser->get($userId);
                $count = $stepsForUser ? $stepsForUser->count() : 0;

                if ($count >= 4) {
                    $toCreateAchievement[] = [
                        'account_id' => $userId,
                        'text'       => $targetText,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    $toCreateGameStep[] = [
                        'to_user_id' => $userId,
                        'text'       => $targetText,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // 3. Массовое создание достижений (1 INSERT ... VALUES)
            if (!empty($toCreateAchievement)) {
                Achivment::insert($toCreateAchievement);
            }

            // 4. Массовое создание GameStep (1 INSERT ... VALUES)
            if (!empty($toCreateGameStep)) {

                GameStep::insert($toCreateGameStep);
            }

            // 5. Массовое удаление для пользователей БЕЗ спецроли (1 DELETE)
            if (!empty($nonSpecialUsers)) {
                GameStep::whereIn('to_user_id', $nonSpecialUsers)
                    ->where('text', $targetText)
                    ->delete();
            }
        });


        $maxCount = -1;
        $userWithMaxCount = null;
        foreach ($this->params['users'] as $user) {
            if ($user['count'] > $maxCount) {
                $maxCount = $user['count'];
                $userWithMaxCount = $user;
            }
        }
        if ($userWithMaxCount['character'] == 'N') {
            GameStep::create([
                'comment' => 'Фиксируем данный game step если днем игрок собрал максимум голосов. Потом проверим: если победил и нет game step с текущим game id, то дадим ачивку. В конце игры удалим эти game step по полю nev_ruck',
                'from_user_id' => $userWithMaxCount['id'],
                'game_id'      => $this->game['id'],
                'char' => 'nev_ruck',
                'step' => $this->step,
                'text' => 'Невидимая рука: Победить за мафию, ни разу не собрав максимум голосов в дневное голосование (не быть в топе подозреваемых).',
            ]);
        }




        if ($this->params['countDays'] == 1) {
            foreach ($this->params['users'] as $user) {
                if ($user['role'] == 'Дон') {
                    GameStep::create([
                        'from_user_id' => $user['id'],
                        'game_id'      => $this->game['id'],
                        'text' => 'Громкое падение: был казненным первым на голосовании, будучи Доном мафии 3 раза.',
                        'char' => 'don',
                        'step' => $this->step,
                    ]);
                    $game_steps = GameStep::where('from_user_id', $user['id'])
                        ->where('game_id', $this->game['id'])
                        ->where('char', 'don')
                        ->get();
                    if (count($game_steps) >= 3) {
                        Achivment::create([
                            'account_id' => $user['id'],
                            'text' => 'Громкое падение: был казненным первым на голосовании, будучи Доном мафии 3 раза.',
                            'description' => 'Трижды я брал в руки карту Дона. Трижды я смотрел в глаза городу и видел в них приговор. Мой авторитет рушился раньше, чем я успевал его построить. Судьба смеялась надо мной, делая меня главной мишенью в первый же день. Громкое падение — это когда твой трон становится эшафотом, а корона — петлёй. Три раза я умирал до того, как моя мафия успевала шевельнуться.',
                        ]);
                    }
                }
            }
        }


        if ($this->params['countDays'] == 1) {
            foreach ($this->killed_users as $user) {
                if ($user->role == 'Бомба') {
                    Achivment::create([
                        'account_id' => $user->id,
                        'table_id' => $user->table_id,
                        // 'step' => $this->step,
                        'text' => 'Бомба: была убита первою на голосовании, будучи Бомбой.',
                    ]);
                }
            }
        }
        foreach ($this->killed_users as $user) {
            $game_step = GameStep::where('game_id', $user->game_id)
                ->where('to_user_id', $user->id)
                ->where('char', 'tot_esche')
                ->where('text', $this->game['countDays'])
                ->first();
            if ($game_step) {
                GameStep::create([
                    'account_id' => $game_step->from_user_id,
                    'game_id'      => $user->game_id,
                    'table_id' => $user->table_id,
                    'char' => 'tot_esche',
                    'step' => $this->step,
                    'text' => 'Тот еще взяточник: за Приспешника, «компрометировать» игрока, который на следующий день будет казнён по результатам голосования (3 раза).',
                ]);
                $game_steps = GameStep::where('account_id', $game_step->from_user_id)
                    ->where('game_id', $user->game_id)
                    ->where('char', 'tot_esche')
                    ->get();
                if (count($game_steps) >= 3) {
                    Achivment::create([
                        'account_id' => $game_step->from_user_id,
                        'table_id' => $user->table_id,
                        'text' => 'Тот еще взяточник: за Приспешника, «компрометировать» игрока, который на следующий день будет казнён по результатам голосования (3 раза).',
                        'description' => 'Правда — это не то, что есть. Это то, во что верят. Я просто... подкидываю уголь в топку их веры. Десятки раз раз я брал чью-то невинность, макал её в чернила лжи и возвращал в игру. И Десятки раз город сжирал свою же честность, приняв её за порок. Я не убивал их. Я просто рисовал мишени. А толпа... о, толпа никогда не промахивается.',
                    ]);
                }
            }
        }
    }

    public function check($game_step)
    {
        $from_user_id = $game_step->from_user_id;
        $to_user_id = $game_step->to_user_id;

        switch ($game_step->action) {
            case 'mafiaToCivillianNoRole':

                if (in_array($to_user_id, $this->killed_users->pluck('id')->toArray())) {
                    // Мафия проголосвала в мирного без роли и он казнен
                    $this->getRating($game_step->take_rating_where_true, $game_step, 'Мафия проголосвала в мирного без роли и он казнен');
                } else {
                    // Мафия проголосвала в мирного без роли и он не казнен
                    $this->getRating($game_step->take_rating_where_false, $game_step, 'Мафия проголосвала в мирного без роли и он не казнен');
                }
                $game_step->is_confirmed = 1;
                $game_step->save();
                break;
            case 'mafiaToCivillianWithRole':
                if (in_array($to_user_id, $this->killed_users->pluck('id')->toArray())) {
                    // Мафия проголосвала в мирного c ролью и он казнен
                    $this->getRating($game_step->take_rating_where_true, $game_step, 'Мафия проголосвала в мирного c ролью и он казнен');
                } else {
                    // Мафия проголосвала в мирного с ролью и он не казнен
                    $this->getRating($game_step->take_rating_where_false, $game_step, 'Мафия проголосвала в мирного с ролью и он не казнен');
                }
                $game_step->is_confirmed = 1;
                $game_step->save();
                break;
            case 'mafiaToWerewolf':
                $game_step->is_confirmed = 1;
                $game_step->save();
                break;
            case 'civillianToMafia':
                if (in_array($to_user_id, $this->killed_users->pluck('id')->toArray())) {
                    // Мирный проголосовал в мафию и мафия казнена
                    $this->getRating($game_step->take_rating_where_true, $game_step, 'Мирный проголосовал в мафию и мафия казнена');
                } else {
                    // Мирный проголосовал в мафию и мафия не казнена
                    $this->getRating($game_step->take_rating_where_false, $game_step, 'Мирный проголосовал в мафию и мафия не казнена');
                }
                $game_step->is_confirmed = 1;
                $game_step->save();
                break;
            case 'advocate':
                // Защита от повторного срабатывания: если шаг уже подтвержден, ничего не делаем
                if ($game_step->is_confirmed == 1) {
                    break;
                }

                // 1. Ищем цель защиты Адвоката в массиве текущей игры
                $targetUser = null;
                foreach ($this->params['users'] as $user) {
                    if ($user['id'] == $game_step->to_user_id) {
                        $targetUser = $user;
                        break;
                    }
                }

                if (!$targetUser) {

                    $game_step->is_confirmed = 1;
                    $game_step->save();
                    break;
                }

                // 2. Находим реальный максимум голосов на столе (исключая 0 голосов)
                $maxVotes = 0;
                $userWithMaxVotes = null;

                foreach ($this->params['users'] as $user) {
                    if ($user['count'] > $maxVotes) {
                        $maxVotes = $user['count'];
                        $userWithMaxVotes = $user;
                    }
                }

                // Был ли этот игрок действительно под угрозой казни?
                // Проверяем, что максимум голосов > 0 и цель защиты совпадает с тем, кто набрал максимум
                $isActuallySavedFromExecution = ($maxVotes > 0 && $userWithMaxVotes !== null && $userWithMaxVotes['id'] == $targetUser['id']);

                // 3. Распределение рейтингов и ачивок
                if ($targetUser['character'] == 'Y') { // Защитили МИРНОГО

                    if ($isActuallySavedFromExecution) {
                        // | Защита | Спас мирного от казни | 12 | (именно отмена казни)

                        if (trim($targetUser['role']) === 'Мирный') {
                            $this->getRating(3, $game_step, '| Защита | Спас мирного без роли | +3 |');
                        } else {
                            $this->getRating(5, $game_step, '| Защита | Спас мирного с ролью | +5 |');
                        }
                        // $this->getRating(12, $game_step, 'Защита | Спас мирного от казни | 12 | (именно отмена казни)');

                        // Логика временных достижений
                        TempAchivment::create([
                            'from_user_id' => $game_step->from_user_id,
                            'to_user_id' => $game_step->to_user_id,
                            'achivment' => 'master_otsrcohcki',
                        ]);

                        $tempsCount = TempAchivment::where('from_user_id', $game_step->from_user_id)
                            ->where('achivment', 'master_otsrcohcki')
                            ->count(); // Используем count() вместо ->get() для оптимизации памяти

                        if ($tempsCount == 5) {
                            Achivment::firstOrCreate([
                                'account_id' => $game_step->from_user_id,
                                'text' => 'Мастер отсрочки: за адвоката спас мирного на дневном голосовании 5 раз',
                                'description' => 'Толпа ревёт. Они уже готовы отправить невиновного на эшафот. Ещё секунда — и приговор свершится, необратимый и страшный в своей ошибке. Но ты встаёшь между ними и жертвой. Твоё слово звучит тихо, но в нём — сила, перед которой отступает даже гнев толпы. "Не сегодня", — говоришь ты. И город отступает. 5 раз ты вырывал мирных из лап “сомнительного” правосудия. 5 раз ошибка не стала трагедией. Ты не просто адвокат. Ты — тот, кто даёт городу шанс одуматься.',
                            ]);
                        }

                        if ($tempsCount == 20) {
                            Achivment::firstOrCreate([
                                'account_id' => $game_step->from_user_id,
                                'text' => 'Нерушимая защита: за адвоката спас мирного на дневном голосовании 20 раз',
                                'description' => 'Они кричали. Они требовали крови. Они уже видели в нём врага и готовы были разорвать его голыми руками. А ты просто встал и сказал: "Нет". Один звук. Одно слово. И толпа замерла. 20 раз ты бросал вызов их гневу. 20 раз твоё слово оказывалось сильнее их ярости. Ты не просто защищаешь — ты учишь город не ошибаться. Ты напоминаешь им, что правосудие — это не месть, а поиск истины. И каждый раз, когда невиновный уходит с эшафота живым, в городе становится тише. Спокойнее. Человечнее. Ты — не адвокат. Ты — совесть этого города. И она ещё не онемела.',
                            ]);
                        }

                        // Проверка на спасение важной роли
                        $importantRoles = ['Шериф', 'Доктор', 'Детектив', 'Бомба', 'Палач'];
                        if (in_array($targetUser['role'], $importantRoles)) {
                            Achivment::firstOrCreate([
                                'account_id' => $game_step->from_user_id,
                                'text' => 'Защитник на суде: играя за адвоката, спас от казни важного мирного жителя с ролью (Шерифа, Детектива, Доктора, Палача, Бомбу)',
                                'description' => 'Они уже видели в нём врага. Толпа ревела, требуя крови, и никому не было дела до того, что именно этот человек мог спасти город. Шериф, Детектив, Доктор, Каратель, Бомба — неважно. Важно было другое: ты знал, что без него город погибнет. И ты встал. Один против всех. Твоё слово прозвучало тихо, но в нём было столько уверенности, что толпа замерла. "Он нужен нам живым", — сказал ты. И они отступили. В этот день ты защищал не просто человека. Ты защищал надежду города на спасение. Ты — не адвокат. Ты — тот, кто помнит, что иногда правда важнее мести.',
                            ]);
                        }
                    } else {
                        // | Защита | Защитил мирного, казнь не состоялась | 3 |
                        $this->getRating(2, $game_step, 'Защита | Защитил мирного, казнь не состоялась | 2 |');
                    }
                } elseif ($targetUser['character'] == 'N') { // Защитили МАФИЮ

                    if ($isActuallySavedFromExecution) {
                        // Спас мафию от реальной казни
                        Achivment::firstOrCreate([
                            'account_id' => $game_step->from_user_id,
                            'text' => 'Адвокат дьявола: играя за адвоката, спас мафию от казни на голосовании',
                            'description' => 'Ты знал? Нет, ты не знал. Ты просто видел человека, которого вот-вот разорвёт толпа, и не смог промолчать. Его речь была прекрасна. Убедительна. Безупречна. Ты поверил. "Невиновный" ушёл со слезами благодарности на глазах. А ночью он пришёл к тебе с ножом. Ты смотрел в его глаза и видел в них насмешку. Ты спас мафию. Ты — адвокат дьявола. И в аду для тебя уже зарезервировано место в первом ряду.',
                        ]);
                    }
                }

                // 4. ОБЯЗАТЕЛЬНОЕ ПОДТВЕРЖДЕНИЕ ШАГА
                $game_step->is_confirmed = 1;
                $game_step->save();

                break;

            case 'prispeshnick':
                // 1. Защита от повторного срабатывания текущего объекта шага
                if ($game_step->is_confirmed == 1) {
                    break;
                }

                // 2. Защита от физических дубликатов в БД
                $alreadyProcessed = GameStep::where('game_id', $this->game['id'])
                    ->where('action', 'prispeshnick')
                    ->where('step', $this->step)
                    ->where('is_confirmed', 1)
                    ->exists();

                if ($alreadyProcessed) {
                    $game_step->is_confirmed = 1;
                    $game_step->save();
                    break;
                }

                // 3. Находим ЦЕЛЬ приспешника в параметрах игры
                $targetUser = null;
                foreach ($this->params['users'] as $user) {
                    if ($user['id'] == $game_step->to_user_id) {
                        $targetUser = $user;
                        break;
                    }
                }

                if (!$targetUser) {
                    \Illuminate\Support\Facades\Log::error("Приспешник: Цель с ID {$game_step->to_user_id} не найдена в параметрах.");
                    $game_step->is_confirmed = 1;
                    $game_step->save();
                    break;
                }

                // 4. Вычисляем, был ли игрок казнен
                // Вариант А: Ищем максимальное число голосов на столе
                $maxCount = 0;
                foreach ($this->params['users'] as $user) {
                    if ($user['count'] > $maxCount) {
                        $maxCount = $user['count'];
                    }
                }

                // Игрок считается под казнью, если у него голосов равен максимуму (и максимум > 0)
                // Либо если в вашей системе после казни у него меняется статус жизней на 0:
                $isTargetActuallyExecuted = false;

                if ($maxCount > 0 && $targetUser['count'] == $maxCount) {
                    $isTargetActuallyExecuted = true;
                }
                // На случай, если счетчики уже сбросились в 0, но игрок только что умер (был жив, стал мертв на этом шаге)
                // Вы можете раскомментировать строку ниже, если counts обнуляются до вызова кейса:
                // elseif ((int)$targetUser['is_alive'] === 0) { $isTargetActuallyExecuted = true; }


                // 5. ЖЕСТКОЕ ОБНОВЛЕНИЕ СТАТУСА В БД ДО НАЧИСЛЕНИЯ РЕЙТИНГА
                GameStep::where('id', $game_step->id)->update(['is_confirmed' => 1]);
                $game_step->is_confirmed = 1;

                // 6. Начисление рейтинга на основе результатов голосования
                if ($targetUser['character'] == 'Y') {
                    if ($isTargetActuallyExecuted) {
                        // | Скомпрометировал | «Скомпрометированный» мирный казнён | 10 |
                        $this->getRating(3, $game_step, 'Скомпрометировал | «Скомпрометированный»  казнён | 3 |');
                    } else {
                        // | Скомпрометировал | «Скомпрометированный» проверен, не казнён | 5 |
                        // $this->getRating(5, $game_step, 'Скомпрометировал | «Скомпрометированный» проверен, не казнён | 5 |');
                    }
                }

                break;
        }
    }

    public function getRating($rating, $game_step, $text_ = null)
    {
        $this->from_user = Account::find($game_step->from_user_id);
        $this->from_user->rating += $rating;
        $this->from_user->save();

        $text = $game_step->text;

        if ($text_ != '' || $text_ != null) {
            $text = $text_;
        }

        file_put_contents('rating-log.txt', 'Дали рейтинга для ' . $this->from_user->name . ', количество :  ' . $rating . 'за действие ' . $text . '' . "\n", FILE_APPEND);
        return 'Дали рейтинга:' . $rating;
    }

    public function checkAchivments()
    {

        StaticAchivmentService::checkForMasterOtsrochcki($this->params);
        //Гений саботажа: будучи мирным голосовал в роль Бомбы и она днем взорвалась, забрав с собой 2 и более мирных..
        $killed_by_bomb_ids = array_column(array_column($this->params['resumeKillUsers'], 'user'), 'id');
        //смотрим что взорвало двух
        if (count($killed_by_bomb_ids) >= 2) {
            $temp_saboters = TempAchivment::where('game_id', $this->game['id'])
                ->where('achivment', 'saboter')
                ->whereIn('to_user_id', $killed_by_bomb_ids)
                ->get();

            foreach ($temp_saboters as $temp_saboter) {

                Achivment::create([
                    'account_id' => $temp_saboter->from_user_id,
                    'text' => 'Гений саботажа',
                    'description' => 'Тактический анализ завершён! Цель определена! Решающий голос отдан!... Эй, а почему все на меня так смотрят? И почему тот парень, за которого я голосовал, оказался... ой. Бум. Кажется, я только что в прямом эфире похоронил часть мирных. Ну, бывает. Просто он был неубедителен!'
                ]);
                $temp_saboter->delete();
            }
        }
    }
}
