<?php

namespace App\Http\Services;

use App\Models\Achivment;
use App\Models\GameStep;
use App\Models\TempFigurant;

class NightRatingService
{
    public $from_user;
    public $to_user;
    public $params;
    public $step;


    public function __construct($from_user, $to_user, $params, $step)
    {
        $this->from_user = (object) $from_user;

        $this->to_user = (object) $to_user;
        $this->params = $params;
        $this->params['game_id'] = $from_user->game_id;
        $this->step = $step;
        $this->route();
    }

    public function route()
    {



        if ($this->from_user->role == 'Шериф') {
            $this->routeSheriff();
        } elseif ($this->from_user->role == 'Доктор') {

            $this->routeDoctor();
        } elseif ($this->from_user->role == 'Каратель') {
            $this->routePunisher();
        } elseif ($this->from_user->role == 'Фредди') {
            $this->routeFreddy();
        } elseif ($this->from_user->role == 'Журналист') {
            $this->routeJournalist();
        } elseif ($this->from_user->role == 'Детектив') {
            $this->routeDetective();
        } elseif ($this->from_user->role == 'Адвокат') {
            $this->routeAdvocate();
        } elseif ($this->from_user->role == 'Судья') {
            $this->routeJudge();
        } elseif ($this->from_user->role == 'Дон') {
            $this->routeDon();
        } elseif ($this->from_user->role == 'Приспешник') {

            $this->routePrispeshnick();
        } elseif ($this->from_user->role == 'Стрелочник') {
            $this->routeStrelochnik();
        } elseif ($this->from_user->role == 'Бомба') {
            $this->routeBomb();
        } elseif ($this->from_user->role == 'Оборотень') {
            $this->routeOboroten();
        }
    }

    public function routeSheriff()
    {

        StaticAchivmentService::checkForBlind($this->to_user, $this->from_user, $this->params);
        StaticAchivmentService::checkForLoudRazoblachenie($this->to_user, $this->from_user, $this->params);
        StaticAchivmentService::checkForGarant($this->to_user, $this->from_user, $this->params);
        StaticAchivmentService::checkForDetector($this->to_user, $this->from_user, $this->params);

        $this->getRating(1, 'Базовое действие');
        if ($this->to_user->role == 'Приспешник') {
            GameStep::create([
                'game_id' => $this->to_user->game_id,
                'table_id' => $this->to_user->table_id,
                'account_id' => $this->to_user->id,
                'char' => 's_and_v',
                'text' => $this->params['countNights'],
                'step' => $this->step,
            ]);
        }
        if ($this->to_user->role == 'Оборотень') {
            GameStep::create([
                'game_id' => $this->params['game_id'],
                'account_id' => $this->to_user->id,
                'char' => 'master_intrigi',
                'step' => $this->step,
            ]);
        }
        //| Проверка | Проверил «запачканного» приспешником | 0 |
        if ($this->from_user->role == 'Шериф' && in_array('Приспешник', $this->params['toUser']['buff_role'])) {
            $this->getRating(0);
        }
        // | Проверка | Нашёл мафию | 10 |
        elseif ($this->from_user->role == 'Шериф' && $this->to_user->character == 'N') {

            $this->getRating(5, 'Нашёл мафию');
            if ($this->params['countNights'] == 1) {
                $this->getRating(2, 'За первую ночь');
            }
        }
        // | Проверка | Нашёл мирного | 2 |
        elseif ($this->from_user->role == 'Шериф' && $this->to_user->character == 'Y') {
            // $this->getRating(2, 'Нашёл мирного');
        }
        //| Проверка | Проверил Оборотня (до / после активации)
        elseif ($this->from_user->role == 'Шериф' && $this->to_user->role == 'Оборотень') {
            if ($this->to_user->character == 'Y') {
                // До активации
                // $this->getRating(2, 'Проверил Оборотня (до активации)');
            } else {
                // После активации
                $this->getRating(5, 'Проверил Оборотня (после активации)');
            }
        } elseif ($this->from_user->role == 'Шериф' && $this->to_user->role == 'Оборотень') {
            if ($this->to_user->is_active == 0) {
                // До активации
                $this->getRating(2, 'Проверил Оборотня (до активации)');
            } else {
                // После активации
                $this->getRating(10, 'Проверил Оборотня (после активации)');
            }
        }
        // ДОБАВИТЬ | Проверка | Проверил «запачканного» | 0 |
    }


    public function routePunisher()
    {
        $this->getRating(1, 'Базовый рейтинг');
        if ($this->to_user->role == 'Стрелочник') {
            //| Выстрел | Выстрелил в Стрелочника | 2
            $this->getRating(2, 'Выстрелил в Стрелочника');
            return;
        }

        GameStep::create([
            'game_id' => $this->to_user->game_id,
            'table_id' => $this->to_user->table_id,
            'from_user_id' => $this->from_user->id,
            'to_user_id' => $this->to_user->id,
            'action' => 'punisher',
            'status' => 'Каратель',
            'is_binary' => 0,
            'step' => $this->step,
        ]);
    }



    public function routeDoctor()
    {

        StaticAchivmentService::checkForPacient($this->to_user, $this->from_user, $this->params);
        StaticAchivmentService::checkForDoctorHaos($this->to_user, $this->from_user, $this->params);
        StaticAchivmentService::checkForAngel($this->to_user, $this->from_user, $this->params);
        $this->getRating(2, 'Базовое действие');
        GameStep::create([
            'game_id' => $this->to_user->game_id,
            'table_id' => $this->to_user->table_id,
            'from_user_id' => $this->from_user->id,
            'to_user_id' => $this->to_user->id,
            'action' => 'doctorHealed',
            'status' => 'Доктор вылечил',
            'take_rating_where_true' => 0, // если казнен
            'take_rating_where_false' => 0, // если не казнен
            'is_binary' => 0,
            'step' => $this->step,
        ]);
    }

    public function getRating($rating, $text = null)
    {

        $this->from_user->rating += $rating;

        $this->from_user->save();
        file_put_contents('rating-log.txt', 'Дали рейтинга для ' . $this->from_user->name . ', количество : ' . $rating . ' за действие ' . $text . '' . "\n", FILE_APPEND);
        return $this->from_user->rating;
    }


    public function routeBomb()
    {

        foreach ($this->params['users'] as $user) {
            if ($user['id'] == $this->to_user->id) {
                if ($user['character'] == 'N') {
                    //| Чтение стола | За каждого заминированного мафиози | +2 |
                    $this->getRating(2, '| Чтение стола | За каждого заминированного мафиози | +2 |');
                    return;
                }
            }
        }
        GameStep::create([
            'game_id' => $this->to_user->game_id,
            'table_id' => $this->to_user->table_id,
            'from_user_id' => $this->from_user->id,
            'to_user_id' => $this->to_user->id,
            'action' => 'bomb',
            'status' => 'Бомба',
            'is_binary' => 0,
            'role' => 'Бомба',
            'text' => 'Бомба',
            'step' => $this->step,
        ]);
    }

    public function routeFreddy()
    {
        $this->getRating(1, 'Базовый рейтинг');
        GameStep::create([
            'game_id' => $this->to_user->game_id,
            'table_id' => $this->to_user->table_id,
            'from_user_id' => $this->from_user->id,
            'to_user_id' => $this->to_user->id,
            'action' => 'freddy',
            'status' => 'Фредди',
            'is_binary' => 0,
            'role' => 'Фредди',
            'step' => $this->step,
            'text' => 'Фредди'
        ]);
    }


    public function routeDetective()
    {



        $this->getRating(1, 'Базовый рейтинг');
        foreach ($this->params['users'] as $user) {
            if ($user['id'] == $this->to_user->id) {
                if ($user['role'] == 'Оборотень' && $user['character'] == 'Y') {
                    //| Проверка | Проверил Оборотня (до активации) | 4 |
                    $this->getRating(4, 'Проверил Оборотня (до активации)');
                    return;
                }
                if ($user['role'] == 'Оборотень' && $user['character'] == 'N') {
                    //| Проверка | Проверил Оборотня (после активации) | 10 |
                    $this->getRating(10, 'Проверил Оборотня (после активации)');
                    return;
                }
            }
        }

        if ($this->to_user->role == 'Оборотень') {
            GameStep::create([
                'game_id' => $this->from_user->game_id,
                'account_id' => $this->to_user->id,
                'char' => 'master_intrigi',
                'step' => $this->step,
            ]);
        }

        if ($this->to_user->role == 'Приспешник') {
            GameStep::create([
                'game_id' => $this->to_user->game_id,
                'table_id' => $this->to_user->table_id,
                'account_id' => $this->to_user->id,
                'char' => 's_and_v',
                'text' => $this->params['countNights'],
                'step' => $this->step,
            ]);
        }

        //| Проверка | Нашёл мафию | 10 |
        if ($this->to_user->character == 'N') {
            //| Проверка | Проверил «запачканного» | 0 |
            foreach ($this->params['users'] as $user) {
                if ($user['id'] == $this->to_user->id) {
                    if (in_array('Шулер', $user['buff_role'])) {
                        $this->getRating(0);
                        return;
                    }
                }
            }

            $this->getRating(5, 'Нашёл мафию');
            if ($this->params['countNights'] == 1) {
                $this->getRating(2, 'За первую ночь');
            }
            //проверим убьет ли в дальнейшем найденную мафию
            GameStep::create([
                'game_id' => $this->from_user->game_id,
                'from_user_id' => $this->from_user->id,
                'to_user_id' => $this->to_user->id,
                'action' => 'detective',
                'status' => '',
                'take_rating_where_true' => 10, // если убил
                'take_rating_where_false' => 0, // если не убил
                'role' => 'Детектив',
                'text' => 'Убил / не убил мафию',
                'step' => $this->step,
            ]);
            return;
        }

        if ($this->to_user->character == 'Y') {
            //| Проверка | Проверил мирного | 4 |
            $this->getRating(4, 'Проверил мирного');
            return;
        }
    }


    public function routeJournalist()
    {

        if ($this->to_user->role == 'Приспешник') {
            GameStep::create([
                'game_id' => $this->to_user->game_id,
                'table_id' => $this->to_user->table_id,
                'account_id' => $this->to_user->id,
                'char' => 's_and_v',
                'text' => $this->params['countNights'],
                'step' => $this->step,
            ]);
        }

        GameStep::create([
            'from_user_id' => $this->from_user->id,
            'to_user_id' => $this->to_user->id,
            'action' => 'journalist',
            'status' => 'Жуналист сравнил',
            'is_binary' => 0,
            'role' => 'Журналист',
            'step' => $this->step,
            'text' => 'Журналист сравнил',
            'game_id' => $this->from_user->game_id
        ]);

        if ($this->to_user->role == 'Оборотень') {
            GameStep::create([
                'game_id' => $this->from_user->game_id,
                'account_id' => $this->to_user->id,
                'char' => 'master_intrigi',
                'step' => $this->step,
            ]);
        }
    }

    public function routeAdvocate()
    {

        $this->getRating(2, 'Базовое действие');
        StaticAchivmentService::checkForMasterOtsrochcki($this->to_user, $this->from_user, $this->params);
        GameStep::create([
            'game_id' => $this->to_user->game_id,
            'table_id' => $this->to_user->table_id,
            'from_user_id' => $this->from_user->id,
            'to_user_id' => $this->to_user->id,
            'action' => 'advocate',
            'status' => 'Адвокат',
            'is_binary' => 0,
            'step' => $this->step,
            'role' => 'Адвокат',
            'text' => 'Адвокат'
        ]);
    }

    public function routeJudge()
    {


        $this->getRating(1, 'Базовое действие');
        StaticAchivmentService::checkForFigurant($this->to_user, $this->from_user, $this->params);

        // | Помеха | Наложил вето на Оборотня | 5 |
        if ($this->to_user->role == 'Оборотень') {
            // $this->getRating(5, 'Наложил вето на Оборотня');
            return;
        }
        //| Помеха | Наложил вето на мафию| 10 |
        if ($this->to_user->character == 'N') {
            $this->getRating(1, 'Наложил вето на мафию');
            $game_steps = GameStep::where('to_user_id', $this->from_user->id)
                ->where('text', 'Король Вето:10 раз наложить Вето именно на мафию')
                ->get();

            if (count($game_steps) >= 9) {
                Achivment::create([
                    'account_id' => $this->from_user->id,
                    'text' => 'Король Вето:10 раз наложить Вето именно на мафию',
                    'description' => 'Они привыкли, что их слово — последнее. Что ложь, брошенная в толпу, становится приговором. Но каждый раз, когда мафия открывала рот, чтобы убить, твой молоток опускался раньше. 10 раз ты выбивал оружие из их рук. 10 раз их голос тонул в тишине, не долетев до чужих ушей. В городе поговаривают: если судья поднял молоток — мафия может даже не начинать говорить. Всё равно не услышат.',
                ]);
            } else {
                GameStep::create([
                    'game_id' => $this->to_user->game_id,
                    'table_id' => $this->to_user->table_id,
                    'from_user_id' => $this->from_user->id,
                    'text' => 'Король Вето:10 раз наложить Вето именно на мафию',
                    'game_id' => $this->from_user->game_id,
                    'step' => $this->step,
                ]);
            }
            if ($this->to_user->character == 'N') {
                $game_steps = GameStep::where('to_user_id', $this->from_user->id)
                    ->where('game_id', $this->from_user->game_id)
                    ->where('text', 'Судья от Бога: 3 раза за игру дать Вето именно мафии')
                    ->get();
                if (count($game_steps) >= 2) {
                    Achivment::create([
                        'account_id' => $this->from_user->id,
                        'text' => 'Судья от Бога: 3 раза за игру дать Вето именно мафии',
                        'description' => 'Трижды прав — трижды правосудие',
                    ]);
                } else {
                    GameStep::create([
                        'game_id' => $this->to_user->game_id,
                        'table_id' => $this->to_user->table_id,
                        'from_user_id' => $this->from_user->id,
                        'text' => 'Судья от Бога: 3 раза за игру дать Вето именно мафии',
                        'game_id' => $this->from_user->game_id,
                        'step' => $this->step,
                    ]);
                }

                $game_steps = GameStep::where('to_user_id', $this->from_user->id)
                    ->where('text', 'Тишина в зале: 30 раз дать Вето именно мафии')
                    ->get();
                if (count($game_steps) >= 29) {
                    Achivment::create([
                        'account_id' => $this->from_user->id,
                        'text' => 'Тишина в зале: 30 раз дать Вето именно мафии',
                        'description' => 'Они выходили на трибуну и говорили. Громко. Убедительно. Красиво. Их речи растекались по залу, как мёд, и город внимал, готовый проглотить любую ложь. Но в конце, когда наступал момент истины, когда поднимались руки для приговора — их рук не было среди поднятых. Слово — это сила. Но голос — это власть. И 30 раз ты оставлял мафию с их словами, без их власти. Говорите, господа мафиози. Говорите сколько угодно. Но решать будет тот, у кого есть голос. А у вас его больше нет.',
                    ]);
                } else {
                    GameStep::create([
                        'game_id' => $this->to_user->game_id,
                        'table_id' => $this->to_user->table_id,
                        'from_user_id' => $this->from_user->id,
                        'text' => 'Тишина в зале: 30 раз дать Вето именно мафии',
                        'game_id' => $this->from_user->game_id,
                        'step' => $this->step,
                    ]);
                }
            }
            return;
        }

        if ($this->to_user->character == 'Y') {
            $game_steps = GameStep::where('to_user_id', $this->from_user->id)
                ->where('game_id', $this->from_user->game_id)
                ->where('text', '«Глушилка канала “Правда”»: дать Вето 3 раза за игру только мирным жителям.')
                ->get();
            if (count($game_steps) >= 2) {
                Achivment::create([
                    'account_id' => $this->from_user->id,
                    'text' => '«Глушилка канала “Правда»: дать Вето 3 раза за игру только мирным жителям.',
                    'description' => 'Ой, а что эта кнопка делает?» — подумал я несколько раз подряд, тыкая наугад. И 3 раза чья-то идеально продуманная ночь разбивалась о мою абсолютно “логичную”помеху. Они строили замки из тайных планов. Я был тем трёхлетним ребёнком с ведёрком, который случайно оказывался рядом. Совпадение? Не думаю. Это талант!',
                ]);
            } else {
                GameStep::create([
                    'game_id' => $this->to_user->game_id,
                    'table_id' => $this->to_user->table_id,
                    'from_user_id' => $this->from_user->id,
                    'text' => '«Глушилка канала “Правда”»: дать Вето 3 раза за игру только мирным жителям.',
                    'game_id' => $this->from_user->game_id,
                    'step' => $this->step,
                ]);
            }
        }
        // | Помеха | Наложил вето на мирного | 1 |
        if ($this->to_user->character == 'Y') {
            // $this->getRating(1, 'Наложил вето на мирного');
            return;
        }
    }

    public function routeDon()
    {


        $this->getRating(2, 'Базовое действие');
        // 1. Проверяем, не обрабатывали ли мы блокировку Дона НА ЭТОМ ШАГЕ (круге) игры ранее
        // Ищем технический шаг или лог начисления за этот круг
        $alreadyProcessed = GameStep::where('game_id', $this->from_user->game_id)
            ->where('action', 'don')
            ->where('from_user_id', $this->from_user->id)
            ->where('step', $this->step)
            ->exists();

        if ($alreadyProcessed) {
            // Если уже один раз за эту ночь зафиксировали блокировку — выходим, ничего не начисляем повторно
            return;
        }
        if ($this->to_user->role == 'Бомба') {
            return;
        }
        //| Блокировка | Заблокировал Оборотня | 1 |
        if ($this->to_user->role == 'Оборотень') {
            // Сначала создаем запись в БД, чтобы заблокировать повторные вызовы
            GameStep::create([
                'game_id' => $this->to_user->game_id,
                'table_id' => $this->to_user->table_id,
                'from_user_id' => $this->from_user->id,
                'step' => $this->step,
                'char' => 'zab_ob',
                'action' => 'don',
                'game_id' => $this->from_user->game_id,
                'text' => 'Огонь по своим: заблокировал/а и убил/а Оборотня',
                'comment' => 'Фиксируем, что заблокировали Оборотня. В конце игры посмотрим, убит ли оборотень. Если есть этот game step и оборотень убит то дадим ачивку. Проверяем в night final service',
                'is_confirmed' => 1 // Помечаем как подтвержденный
            ]);

            // $this->getRating(1, 'Заблокировал Оборотня');
            return;
        }

        //| Блокировка | Заблокировал ключевую мирную роль | 10 |
        if ($this->to_user->role != '' && $this->to_user->role != 'Мирный' && $this->to_user->character == 'Y') {

            // Чтобы заблокировать повторное срабатывание для мирной роли,
            // создаем простой технический шаг-заглушку в историю игры
            GameStep::create([
                'game_id' => $this->to_user->game_id,
                'table_id' => $this->to_user->table_id,
                'from_user_id' => $this->from_user->id,
                'step' => $this->step,
                'char' => 'zab_key_role',
                'action' => 'don',
                'game_id' => $this->from_user->game_id,
                'text' => 'Заблокировал ключевую мирную роль',
                'is_confirmed' => 1
            ]);

            $this->getRating(6, 'Заблокировал ключевую мирную роль');
            return;
        }
    }


    public function routePrispeshnick()
    {
        $this->getRating(2, 'Базовое действие');

        GameStep::create([
            'game_id' => $this->to_user->game_id,
            'table_id' => $this->to_user->table_id,
            'from_user_id' => $this->from_user->id,
            'step' => $this->step,
            'to_user_id' => $this->to_user->id,
            'action' => 'prispeshnick',
            'status' => 'Пришпешник',
            'is_binary' => 0,
            'role' => 'Пришпешник',
            'text' => 'Пришпешник',
            'game_id' => $this->from_user->game_id,
            'table_id' => $this->from_user->table_id
        ]);

        if ($this->to_user->role == 'Мирный' || $this->to_user->role == 'Мафия') {
            //| Пришпешник | Пришпешник убил мирного | 5 |
            $game_steps = GameStep::where('from_user_id', $this->from_user->id)
                ->where('text', '“Упс… Осечка”: за Приспешника заблокировать 5 игроков без роли.')
                ->get();
            if (count($game_steps) == 4) {
                $ach = Achivment::where('account_id', $this->from_user->id)
                    ->where('text', '“Упс… Осечка”: за Приспешника заблокировать 5 игроков без роли.')
                    ->first();
                if (!$ach) {
                    Achivment::create([
                        'account_id' => $this->from_user->id,
                        'text' => '“Упс… Осечка”: за Приспешника заблокировать 5 игроков без роли.',
                        'description' => 'Ты просыпался каждую ночь с важным видом. Ты выбирал цель. Ты концентрировался. Ты блокировал... кого? Петю, бухгалтера? Машу, продавщицу? Коляна, который просто пришёл выпить чаю? 5 раз ты тратил свою единственную блокировку на тех, у кого даже способностей не было. Они даже не заметили, что их заблокировали. Потому что блокировать нечего. Мафия смотрела на тебя и плакала. Не от страха — от смеха. Ты не Приспешник. Ты — "Осечка". Но без тебя было бы скучно. Хотя мафия, возможно, с этим поспорит.',
                    ]);
                }
            } else {
                GameStep::create([
                    'game_id' => $this->to_user->game_id,
                    'table_id' => $this->to_user->table_id,
                    'from_user_id' => $this->from_user->id,
                    'step' => $this->step,
                    'to_user_id' => $this->to_user->id,
                    'text' => '“Упс… Осечка”: за Приспешника заблокировать 5 игроков без роли.'
                ]);
            }
        }


        if ($this->to_user->role == 'Шериф' || $this->to_user->role == 'Детектив' || $this->to_user->role == 'Доктор' || $this->to_user->role == 'Каратель' || $this->to_user->role == 'Стрелочник') {
            GameStep::create([
                'game_id' => $this->to_user->game_id,
                'table_id' => $this->to_user->table_id,
                'from_user_id' => $this->from_user->id,
                'step' => $this->step,
                'text' => 'Призрачный угонщик: за Приспешника 15 раз заблокировал важную роль (шериф, Детектив, Палач, Доктор, Стрелочник).',
            ]);
            $game_steps = GameStep::where('from_user_id', $this->from_user->id)
                ->where('text', 'Призрачный угонщик: за Приспешника 15 раз заблокировал важную роль (шериф, Детектив, Палач, Доктор, Стрелочник).')
                ->get();
            if (count($game_steps) >= 15) {
                Achivment::create([
                    'account_id' => $this->from_user->id,
                    'text' => 'Призрачный угонщик: за Приспешника 15 раз заблокировал важную роль (шериф, Детектив, Палач, Доктор, Стрелочник).',
                    'description' => 'Первая ночь. Они ещё верят в справедливость, в удачу, в честную игру. Я верю в статистику, в метку на карте и в холодный расчёт. Пока Шериф тянется к своему значку, а Доктор проверяет аптечку — моя рука уже легла на их плечо. «Не сегодня», — шепчу я тишине. Десятки «сегодня» так и не наступили. Мой страх — не слабость. Это самый точный прицел в городе.',
                ]);
            }
        }


        GameStep::create([
            'game_id' => $this->to_user->game_id,
            'table_id' => $this->to_user->table_id,
            'from_user_id' => $this->from_user->id,
            'step' => $this->step,
            'to_user_id' => $this->to_user->id,
            'char' => 'tot_esche',
            'game_id' => $this->from_user->game_id,
            'table_id' => $this->from_user->table_id,
            'text' => $this->params['countDays'] + 1
        ]);


        return;
    }

    public function routeStrelochnik()
    {
        GameStep::create([
            'game_id' => $this->to_user->game_id,
            'table_id' => $this->to_user->table_id,
            'from_user_id' => $this->from_user->id,
            'step' => $this->step,
            'to_user_id' => $this->to_user->id,
            'action' => 'strelochnik',
            'status' => 'Стрелочник',
            'is_binary' => 0,
            'role' => 'Стрелочник',
            'text' => 'Стрелочник'
        ]);
    }

    public function routeOboroten()
    {
        if ($this->from_user->character == 'N') {
            GameStep::create([
                'game_id' => $this->to_user->game_id,
                'table_id' => $this->to_user->table_id,
                'from_user_id' => $this->from_user->id,
                'step' => $this->step,
                'to_user_id' => $this->to_user->id,
                'char' => 'paradoks',
                'game_id' => $this->from_user->game_id,
                'table_id' => $this->from_user->table_id
            ]);
        }
    }
}
