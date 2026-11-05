<?php

namespace App\Http\Services;

use App\Models\Account;
use App\Models\Achivment;
use App\Models\GameStep;
use App\Models\TempAchivment;



class NightFinalService
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
        $this->users = $params['users'];
        $this->step = $step;
        $ids = array_column(array_column($params['resumeKillUsers'], 'user'), 'id');
        $this->killed_users = Account::whereIn('id', $ids)->get();
        $this->game = $params;
        $this->game['id'] = $this->users[0]['game_id'];

        $this->params = $params;

        $this->params['game_id'] = $this->users[0]['game_id'];
        $this->handle();
        dd($this->params);
    }

    public function handle()
    {

        $game_steps = GameStep::where('is_confirmed', 0)->get();
        foreach ($game_steps as $game_step) {
            $this->check($game_step);
        }

        $this->checkForAchivments();
        $don = Account::where('role', 'Дон')
            ->where('table_id', $this->users[0]['table_id'])
            ->first();

        $mafias = [];
        foreach ($this->users as $user) {
            if ($user['character'] == 'N') {
                $mafias[] = $user;
            }
        }

        foreach ($this->params['resumeKillUsers'] as $user) {


            if (in_array('Мафия', $user['user']['buff_role']) && !in_array('Доктор', $user['user']['buff_role'])) {
                foreach ($mafias as $mafi) {
                    $acc = Account::where('id', $mafi['id'])->first();
                    $acc->rating += 3;
                    $acc->save();
                    file_put_contents('rating-log.txt', 'Дали рейтинга для ' . $acc->name . ', количество : 3 за участие в убийстве ' . '' . "\n", FILE_APPEND);
                }
            }

            if ($user['text'] == 'убит мафией' && $user['user']['role'] == 'Бомба') {
                $is_all_y = true;
                foreach ($this->params['arrUsersKillBomba'] as $us) {
                    if ($us['character'] == 'N') {
                        $is_all_y = false;
                    }
                }
                if ($is_all_y) {
                    Achivment::create([
                        'text' => 'Король хаоса: за Дона принял/а решение об убийстве, которое привело к взрыву бомбы, где все заминированные игроки мирные.',
                        'table_id' => $don->table_id,
                        'account_id' => $don->id,
                        'description' => 'Я хотел убить их всех. Моя цель была так очевидна. Трижды я нажимал на спуск, и трижды хаос смеялся громче меня. Планировал? Естесственно. Просто нажал на спуск и посмотрел. А было красиво. Безумно, непредсказуемо, катастрофично — но красиво. Если не можешь управлять хаосом, стань его королём.',
                    ]);
                }
            }
            if ($user['text'] == 'убит карателем' && $user['user']['character'] == 'N') {
                $karatel = null;
                foreach ($this->params['users'] as $us) {
                    if ($us['role'] == 'Каратель') {
                        $karatel = $us;
                    }
                }
                GameStep::create([
                    'account_id' => $karatel['id'],
                    'table_id' => $karatel['table_id'],
                    'game_id' => $karatel['game_id'],
                    'step' => $this->step,
                    'char' => 'nochnoi_dozor'
                ]);
                $game_steps = GameStep::where('account_id', $karatel['id'])
                    ->where('char', 'nochnoi_dozor')
                    ->get();

                if (count($game_steps) == 10) {
                    Achivment::create([
                        'account_id' => $karatel['id'],
                        'table_id' => $karatel['table_id'],
                        'text' => 'Ночной дозор: за Ферзя убить 10 мафий.',
                        'description' => 'Я не следил за количеством. Я следил за равновесием. Десятки раз раз я чувствовал, как чаша города кренится под тяжестью лжи, и столько же раз моя пуля была тем самым недостающим граммом, который возвращал её в уровень. Я был не карателем. Я был корректором судьбы.',
                    ]);
                }

                if (count($game_steps) == 40) {
                    Achivment::create([
                        'account_id' => $karatel['id'],
                        'table_id' => $karatel['table_id'],
                        'text' => 'Сезон жатвы: за Ферзя убить 40 мафий.',
                        'description' => '40. Четыре десятка. Сорок душ, решивших, что закон — это просто буквы на бумаге. Сорок раз ты выходил на охоту. Сорок раз твой палец ложился на спусковой крючок. Сорок раз ты не промахнулся. Они прятались по углам, меняли имена, притворялись мирными. Но ты видел их. Всегда. В темноте, в толпе, за улыбками и ложью. Ты не детектив — ты не ищешь правду. Ты палач — ты её вершишь. 40 выстрелов. 40 трупов. И благодарные горожане, которые больше не будут бояться выходить на улицу. Город не знает твоего лица. Но мафия знает твоё имя. И когда наступает ночь, они шепчут: "Только не сегодня. Только не я". Но ты приходишь. Ты всегда приходишь. Потому что ты — Жнец. И твоя жатва ещё не окончена.',
                    ]);
                }

                $game_steps = GameStep::where('account_id', $karatel['id'])
                    ->where('char', 'nochnoi_dozor')
                    ->where('table_id', $karatel['table_id'])
                    ->where('game_id', $karatel['game_id'])
                    ->where('step', $this->step)
                    ->get();

                if (count($game_steps) == 3) {
                    Achivment::create([
                        'account_id' => $karatel['id'],
                        'table_id' => $karatel['table_id'],
                        'text' => 'Неотвратимость: за Палача убить 3 мафии только за одну игру.',
                        'description' => 'Мне не нужны были доказательства. Мне нужны были результаты. Сколько раз моя пуля находила цель раньше, чем у города находились слова для обвинения. Они называли это самосудом. Я называл это эффективностью. Правосудие — это не процесс. Это акт. И я совершал его снова и снова.',
                    ]);
                }
            }

            if ($user['text'] == 'убит карателем' && $user['user']['role'] == 'Оборотень' && $user['user']['character'] == 'Y') {
                $karatel = null;
                foreach ($this->params['users'] as $us) {
                    if ($us['role'] == 'Каратель') {
                        $karatel = $us;
                    }
                }
                GameStep::create([
                    'account_id' => $karatel['id'],
                    'table_id' => $karatel['table_id'],
                    'game_id' => $karatel['game_id'],
                    'char' => 'ohot_na_prizrakov',
                    'step' => $this->step,
                ]);

                $game_steps = GameStep::where('account_id', $karatel['id'])
                    ->where('char', 'ohot_na_prizrakov')
                    ->get();
                if (count($game_steps) == 4) {
                    Achivment::create([
                        'account_id' => $karatel['id'],
                        'table_id' => $karatel['table_id'],
                        'text' => 'Охотник на призраков: за ФЕРЗЯ убить Оборотня до активации 4 раза.',
                        'description' => 'Они не оставляли следов. Для проверок они были чисты. Но я не искал следы — я искал пустоту, идеальную маскировку, ту самую неестественную правильность. Каждый раз моя пуля находила того, кого не мог найти ни один детектив. Я убивал миф, пока он ещё не проснулся кошмаром. Я не ждал, когда угроза проявится. Я вычислял саму возможность её существования и стирал с доски. Лучший Оборотень — мёртвый. Ещё лучше — тот, который убит, не успев стать мафией.',
                    ]);
                }
            }
        }



        if ($this->params['countNights'] == 1) {
            $roles = ['Шериф', 'Детектив', 'Доктор', 'Ферзь', 'Бомба'];

            foreach ($this->params['resumeKillUsers'] as $user) {
                if ($user['text'] == 'убит мафией' && in_array($user['user']['role'], $roles)) {
                    GameStep::create([
                        'from_user_id' => $don->id,
                        'char' => 'oh_za_gol',
                        'table_id' => $don->table_id,
                        'game_id' => $don->game_id,
                        'step' => $this->step,
                        'text' => 'Охотник за охотниками: за Дона 5 раз убить важную роль в первую ночь (Шериф, Детектив, Доктор, Ферзь, Бомба)',
                    ]);
                    $game_steps = GameStep::where('from_user_id', $don->id)
                        ->where('char', 'oh_za_gol')
                        ->where('is_confirmed', 0)
                        ->get();
                    if (count($game_steps) >= 4) {
                        Achivment::create([
                            'account_id' => $don->id,
                            'table_id' => $don->table_id,
                            'text' => 'Охотник за охотниками: за Дона 5 раз убить важную роль в первую ночь (Шериф, Детектив, Доктор, Ферзь, Бомба)',
                            'description' => 'Они думали, что это случайность. Ошибка. Невнимательность. Они не понимали, что я веду учёт. Их лица, их манера говорить, их неприятная правота. В одном городе это был Шериф. Во втором — Палач. Роли менялись. Мой приговор — нет. Система нанимает новых героев. Я нахожу старых врагов.',
                        ]);
                    }
                }
            }
        }


        foreach ($this->params['resumeKillUsers'] as $user) {
            if ($user['text'] == 'убит мафией' && $user['user']['role'] != 'Мирный' && $user['user']['role'] != 'Мафия') {
                GameStep::create([
                    'from_user_id' => $don->id,
                    'char' => 'lich_vrag_sist',
                    'table_id' => $don->table_id,
                    'game_id' => $don->game_id,
                    'step' => $this->step,
                ]);
                $game_steps = GameStep::where('from_user_id', $don->id)
                    ->where('char', 'lich_vrag_sist')
                    ->where('from_user_id', $don->id)
                    ->where('table_id', $don->table_id)
                    ->get();
                if (count($game_steps) >= 4) {
                    Achivment::create([
                        'account_id' => $don->id,
                        'table_id' => $don->table_id,
                        'game_id' => $don->game_id,
                        'text' => 'Личный враг системы: за Дона 4 раза убить ролевого игрока в одной игре, ночью.',
                        'description' => 'Они меняют имена. Меняют лица. Меняют роли. Но не меняют суть. Я помню запах их правды — едкий, как дым. В первом городе он носил звезду Шерифа. Во втором — ствол Палача. Я стёр их всех. Система думает, что нанимает героев. Я знаю, что она выращивает моих старых знакомых. И у меня для них есть традиция: второй визит всегда бесплатен.',
                    ]);
                }
            }
        }

        foreach ($this->params['resumeKillUsers'] as $user) {
            $karatel = null;
            foreach ($this->params['users'] as $us) {
                if ($us['role'] == 'Каратель') {
                    $karatel = $us;
                }
            }
            if ($user['text'] == 'убит карателем' && $user['user']['character'] == 'Y') {
                GameStep::create([
                    'account_id' => $karatel['id'],
                    'table_id' => $karatel['table_id'],
                    'game_id' => $karatel['game_id'],
                    'char' => 'otberite_stvol',
                    'step' => $this->step
                ]);
                $game_steps = GameStep::where('from_user_id', $karatel['id'])
                    ->where('char', 'otberite_stvol')
                    ->where('step', $this->step)
                    ->where('table_id', $karatel['table_id'])
                    ->get();
                if (count($game_steps) >= 3) {
                    Achivment::create([
                        'table_id' => $karatel['table_id'],
                        'account_id' => $karatel['id'],
                        'text' => '"Отберите ствол": за Ферзя убил 3х мирных жителей за игру.',
                        'description' => 'План был прост: если убить всех, мафии негде будет прятаться. Три выстрела. Три чистых гражданина. Но зато теперь поле боя идеально простреливается. Моя логика безупречна. Побочные эффекты — неприятны, но допустимы. Город, прости. Но я очищал территорию.',
                    ]);
                }
            }
        }







        $don = null;
        $bomb = null;
        $is_all_y = true;
        $is_one_bomb = false;
        foreach ($this->params['users'] as $us) {
            if ($us['role'] == 'Дон') {
                $don = $us;
            }
            if ($us['role'] == 'Бомба') {
                $bomb = $us;
            }
        }
        foreach ($this->params['resumeKillUsers'] as $user) {
            if ($user['text'] == 'взорван Бомбой') {
                $is_one_bomb = true;
            }
            if ($user['text'] == 'взорван Бомбой' && $user['user']['character'] == 'N') {
                $is_all_y = false;
            }
        }


        //Видим что есть взорванные бомбой и все взорванные мирные
        if ($is_all_y && $is_one_bomb && isset($bomb)) {
            // Achivment::create([
            //     'account_id' => $don['id'],
            //     'table_id' => $don['table_id'],
            //     'text' => 'Король хаоса: за Дона принял/а решение об убийстве, которое привело к взрыву бомбы, где все заминированные игроки мирные.',
            //     'description' => 'Я хотел убить их всех. Моя цель была так очевидна. Трижды я нажимал на спуск, и трижды хаос смеялся громче меня. Планировал? Естесственно. Просто нажал на спуск и посмотрел. А было красиво. Безумно, непредсказуемо, катастрофично — но красиво. Если не можешь управлять хаосом, стань его королём.',
            // ]);
        }

        $ys_ids = [];
        $lilled_ys_ids = [];
        $bomb = null;
        foreach ($this->params['users'] as $user) {
            if ($user['character'] == 'Y' && $user['is_alive'] == 1) {
                $ys_ids[] = $user['id'];
            }
            if ($user['role'] == 'Бомба') {
                $bomb = $user;
            }
        }
        // unset($ys_ids[$bomb['id']]);
        foreach ($this->params['resumeKillUsers'] as $user) {
            if ($user['text'] == 'взорван Бомбой') {
                $lilled_ys_ids[] = $user['user']['id'];
            }
        }


        $diff1 = array_diff($ys_ids, $lilled_ys_ids); // Есть в первом, но нет во втором
        $diff2 = array_diff($lilled_ys_ids, $ys_ids); // Есть во втором, но нет в первом

        if (empty($diff1) && empty($diff2)) {
            Achivment::create([
                'account_id' => $bomb['id'],
                'table_id' => $bomb['table_id'],
                'text' => 'Простите, виноват!”: играя за Бомбу заминировал и убил всех мирных',
                'description' => 'Ты не просто Бомба. Ты — гений тотального минирования... города. Твой финальный список был настолько точен, что убрал с поля всех, кто мог бы тебя спасти. Мафия могла просто наблюдать за этим великолепным актом самоуничтожения. Браво! (Не повторяй это дома',
            ]);
        }

        $killed_by_bomb = [];
        foreach ($this->params['resumeKillUsers'] as $user) {
            if ($user['text'] == 'взорван Бомбой') {
                $killed_by_bomb[] = $user['user'];
            }
        }
        $count_ns = 0;
        $count_ys = 0;
        foreach ($killed_by_bomb as $k) {
            if ($k['character'] == 'Y') {
                $count_ys++;
            }
            if ($k['character'] == 'N') {
                $count_ns++;
                GameStep::create([
                    'account_id' => $bomb['id'],
                    'table_id' => $bomb['table_id'],
                    'char' => 'arh_ap',
                    'game_id' => $this->params['game_id'],
                    'step' => $this->step,
                ]);
            }
        }

        if ($count_ns >= 2 && $count_ys == 0) {
            Achivment::create([
                'account_id' => $bomb['id'],
                'table_id' => $bomb['table_id'],
                'text' => 'Предвестник Апокалипсиса: за бомбу заминировать мафию и взорваться с ними без мирных жертв (минимум 2 мафии).',
                'description' => 'Сотни раз я решал головоломку. Условия: одна моя жизнь, неограниченное количество тротила, N целей. Решение: все цели уничтожены. Я не оставлял им шанса на выживание. Только на исчезновение. Математика мести — самая точная из наук.',
            ]);
        }
        if ($bomb) {
            $game_steps = GameStep::where('account_id', $bomb['id'])
                ->where('char', 'arh_ap')
                ->get();

            if (count($game_steps) >= 20) {
                Achivment::create([
                    'account_id' => $bomb['id'],
                    'table_id' => $bomb['table_id'],
                    'text' => 'Архитектор Апокалипсиса: за бомбу забрать при взрыве 20 мафиозных душ (за игровую историю)',
                    'description' => 'Задача: N целей, один заряд, нулевая погрешность. Решение найдено. Проверено. Внедрено. Десятки раз. Они верили в случайность, в рассеянность, в человеческий фактор. Я верил в стерильную геометрию взрыва, где радиус поражения равен радиусу их лжи. Условия выполнены. Система — чиста.',
                ]);
            }
            if (count($game_steps) >= 50) {
                Achivment::create([
                    'account_id' => $bomb['id'],
                    'table_id' => $bomb['table_id'],
                    'text' => 'Собиратель урожая:  за бомбу забрать при взрыве 50 мафиозных душ (за игровую историю)',
                    'description' => 'Они думали, что город — шахматная доска. Я думал иначе. Я видел в нём минное поле. Сколько раз моя смерть была не ходом, а сметающей всё лавиной. 40 теней, обращённых в пепел одним решением — моим последним вздохом. Я не играл в их игру. Я перерисовывал карту.',
                ]);
            }
        }


        $freddie = null;
        foreach ($this->params['users'] as $user) {
            if ($user['role'] == 'Фредди') {
                $freddie = $user;
            }
        }
        if (isset($this->params['arrUsersKillFredy']) && $freddie) {
            if ($user['character'] == 'N') {
                GameStep::create([
                    'account_id' => $freddie['id'],
                    'table_id' => $freddie['table_id'],
                    'char' => 'j_blaiz',
                    'game_id' => $freddie['game_id'],
                    'step' => $this->step,
                ]);
                $game_steps = GameStep::where('account_id', $freddie['id'])
                    ->where('char', 'j_blaiz')
                    ->get();



                if (count($game_steps) >= 5) {
                    Achivment::create([
                        'account_id' => $freddie['id'],
                        'table_id' => $freddie['table_id'],
                        'text' => 'Джонни Блэйз: за МСТИТЕЛЯ забрать 5 мафии при смерти.',
                        'description' => 'Каждая моя смерть забирала одного из них. Пять жизней за одну. Они думали, что убивают меня. Они ошибались. Они просто пополняли мою коллекцию.',
                    ]);
                }

                GameStep::create([
                    'account_id' => $freddie['id'],
                    'table_id' => $freddie['table_id'],
                    'game_id' => $freddie['game_id'],
                    'step' => $this->step,
                    'char' => 'yamschik',
                ]);
                $game_steps = GameStep::where('account_id', $freddie['id'])
                    ->where('char', 'yamschik')
                    ->get();

                if (count($game_steps) >= 5) {
                    Achivment::create([
                        'account_id' => $freddie['id'],
                        'table_id' => $freddie['table_id'],
                        'text' => 'Ямщик до преисподни: за МСТИТЕЛЯ забрать с собой при смерти 20  мафиозных душ (за игровую историю).',
                        'description' => 'Моя цена за победу города — собственная жизнь. Сколько раз я заключал этот контракт. И и столько же раз тьма, поглотившая меня, вынуждена была выплюнуть обратно трофеи — души тех, кто решился со мной расплатиться. Я — проигравший, который выигрывает войну.',
                    ]);
                }
            }
            if ($user['role'] == 'Оборотень' && $this->params['countNights'] == 1) {
                Achivment::create([
                    'account_id' => $freddie['id'],
                    'table_id' => $freddie['table_id'],
                    'text' => 'Пророк на одну ночь: играя МСТИТЕЛЕМ, найти Оборотня в первую же ночь и умереть с ним.',
                    'description' => 'Мой дар — видеть чужие кошмары. В первую ночь я увидел худший из всех: кошмар всего города. И осознал: чтобы город это узнал, вестник должен исчезнуть. Я крикнул его имя в бездну — и бездна ответила мне объятиями. Я не выжил, чтобы рассказать. Но мне и не нужно было. Сам факт моей смерти стал уликой.',
                ]);
            }
            if ($user['character'] == 'N' && $this->params['countNights'] == 1) {
                GameStep::create([
                    'account_id' => $freddie['id'],
                    'table_id' => $freddie['table_id'],
                    'game_id' => $freddie['game_id'],
                    'step' => $this->step,
                    'char' => 'sobiratel_dush',
                ]);

                $game_steps = GameStep::where('account_id', $freddie['id'])
                    ->where('char', 'sobiratel_dush')
                    ->get();

                if (count($game_steps) >= 5) {
                    Achivment::create([
                        'account_id' => $freddie['id'],
                        'table_id' => $freddie['table_id'],
                        'text' => 'Собиратель душ: за МСТИТЕЛЯ забрать с собой при смерти 3 мафий в ПЕРВУЮ же ночь.',
                        'description' => 'Они думали, что убийство остановит меня. Они не понимали, что я уже в их головах. Сколько раз моя смерть была не концом расследования, а его кульминацией. Мой последний вздох становился их именем на устах города. Убить меня — всё равно что подписать себе приговор. И они подписывали. Снова и снова.',
                    ]);
                }
            }
        }


        $prispechnik = null;
        foreach ($this->params['users'] as $user) {
            if ($user['role'] == 'Приспешник') {
                $prispechnik = $user;
            }
        }
        if ($prispechnik) {
            $id__ = $this->params['saveNightGame']['idPlayerBlockedThePrespechnik'];
            //игрок которого скомпрометировал Приспешник
            $by_prispechnik = null;
            foreach ($this->params['users'] as $user) {
                if ($user['id'] == $id__) {
                    $by_prispechnik = $user;
                }
            }
            if ($by_prispechnik) {
                $game_steps = GameStep::where('account_id', $by_prispechnik['id'])
                    ->where('char', 's_and_v')
                    ->where('game_id', $by_prispechnik['game_id'])
                    ->where('table_id', $by_prispechnik['table_id'])
                    ->where('text', $this->params['countNights'])
                    ->get();

                if (count($game_steps) == 10) {
                    Achivment::create([
                        'account_id' => $prispechnik['id'],
                        'table_id' => $by_prispechnik['table_id'],
                        'text' => 'Сеятель паники: за Приспешника “компрометировать” игрока которого проверили в эту же ночь 10 раз.',
                        'description' => 'Шериф кричит: «Я нашёл волка!». Город хватает вилы. Я в тени поправляю галстук. Моя работа сделана. Двадцать «волков» были овцами. Но кто считает, когда так весело? Я не создаю ложь. Я создаю повод для правосудия. Самого быстрого на свете.',
                    ]);
                }
                if (count($game_steps) == 30) {
                    Achivment::create([
                        'account_id' => $prispechnik['id'],
                        'table_id' => $by_prispechnik['table_id'],
                        'game_id' => $by_prispechnik['game_id'],
                        'text' => 'Вдохновитель линчевания: за Приспешника “компрометировать” игрока которого проверили в эту же ночь 30 раз. (за игровую историю)',
                        'description' => 'Правда — это глина. Я леплю из неё чудовищ. Сколько раз я подсовывал городу чучело с надписью «мафия», и столько же раз они, с рыком праведного гнева, разрывали его на части. Их ярость была настоящей. Их враг — моим творением. Я не лгу. Я просто ставлю спектакли, где публика исполняет главную роль — роль палача.',
                    ]);
                }
            }
        }

        $is_strelochnick = false;
        $strelochnick = null;
        foreach ($this->params['users'] as $user) {
            if ($user['role'] == 'Стрелочник') {

                if (in_array('Мафия', $user['buff_role'])) {
                    $is_strelochnick = true;
                }
            }
        }
        if ($is_strelochnick && $strelochnick) {
            foreach ($this->params['resumeKillUsers'] as $user) {
                if ($user['text'] == 'убит Стрелочником' && $user['user']['character'] == 'N') {
                    GameStep::create([
                        'account_id' => $strelochnick['id'],
                        'table_id' => $strelochnick['table_id'],
                        'char' => 'agent_karmy',
                        'step' => $this->step,
                        'game_id' => $strelochnick['game_id'],
                    ]);

                    $game_steps = GameStep::where('account_id', $strelochnick['id'])
                        ->where('char', 'agent_karmy')
                        ->where('table_id', $strelochnick['table_id'])
                        ->get();

                    if (count($game_steps) >= 2) {
                        Achivment::create([
                            'account_id' => $strelochnick['id'],
                            'table_id' => $strelochnick['table_id'],
                            'text' => 'Агент кармы: играя за Стрелочника перевел/а выстрел мафии от себя обратно в одного из членов мафии 2 раза за игру.',
                            'description' => 'Бессмертие — это не дар. Это обязанность. Обязанность быть щитом для одних и зеркалом — для других.',
                        ]);
                    }
                }
            }


            foreach ($this->params['resumeKillUsers'] as $user) {
                if ($user['text'] == 'убит Стрелочником' && $user['user']['character'] == 'N') {
                    GameStep::create([
                        'account_id' => $strelochnick['id'],
                        'table_id' => $strelochnick['table_id'],
                        'char' => 'schit_mech',
                        'step' => $this->step,
                    ]);
                    $game_steps = GameStep::where('account_id', $strelochnick['id'])
                        ->where('char', 'schit_mech')
                        ->get();

                    if (count($game_steps) >= 5) {
                        Achivment::create([
                            'account_id' => $strelochnick['id'],
                            'table_id' => $strelochnick['table_id'],
                            'text' => 'Шит механик: играя за Стрелочника перевел/а выстрел мафии 5 раз за игру.',
                            'description' => '',
                        ]);
                    }
                    if (count($game_steps) >= 30) {
                        Achivment::create([
                            'account_id' => $strelochnick['id'],
                            'table_id' => $strelochnick['table_id'],
                            'text' => ' Абсолют: за Стрелочника перевести выстрелы 30 раз в мафию (за игровую историю).',
                            'description' => 'Их пули были для меня вопросами. Мои ответы всегда были одни и те же — меткими, точными и адресованными обратно в темноту. Сколько раз я ловил свинец на лету и возвращал его отправителям с одной лишь пометкой: «Не доставлено. Отправитель более не существует.',
                        ]);
                    }
                }
            }
        }
    }

    public function check($game_step)
    {
        $from_user_id = $game_step->from_user_id;
        $to_user_id = $game_step->to_user_id;

        switch ($game_step->action) {
            case 'doctorHealed':

                foreach ($this->users as $user) {
                    if (in_array('Доктор', $user['buff_role']) && $user['id'] == $to_user_id && in_array('Мафия', $user['buff_role']) && $user['role'] == 'Доктор') {
                        //| Лечение | Спас от выстрела мафии | 4 |
                        file_put_contents('log.txt', 'Спас от выстрела мафии | 4 |' . PHP_EOL, FILE_APPEND);
                        $this->getRating(4, $game_step, '| Лечение | Спас от выстрела мафии | 4 |');
                        $game_step->is_confirmed = 1;
                        $game_step->save();
                        if ($this->params['countNights'] == 1) {
                            $this->getRating(2, $game_step, '+2 за первый ночь');
                        }
                        return;
                    }
                    if (in_array('Доктор', $user['buff_role']) && $user['id'] == $to_user_id && in_array('Мафия', $user['buff_role'])) {
                        //| Лечение | Спас от выстрела мафии | 12 |
                        file_put_contents('log.txt', 'Спас от выстрела мафии | 4 |' . PHP_EOL, FILE_APPEND);
                        $this->getRating(4, $game_step, '| Лечение | Спас от выстрела мафии | 12 |');
                        $game_step->is_confirmed = 1;
                        $game_step->save();
                        if ($this->params['countNights'] == 1) {
                            $this->getRating(2, $game_step, '+2 за первый ночь');
                        }
                        return;
                    }
                }

                foreach ($this->params['users'] as $user) {
                    if (in_array('Доктор', $user['buff_role']) && $user['character'] == 'Y') {
                        // | Лечение | Лечил мирного без выстрела | 1 |
                        // $this->getRating(1, $game_step, '| Лечение | Лечил мирного без выстрела | 1 |');
                        $game_step->is_confirmed = 1;
                        $game_step->save();

                        return;
                    }
                }

                break;

            case 'punisher':

                foreach ($this->params['resumeKillUsers'] as $user) {

                    if (in_array('Каратель', $user['user']['buff_role'])) {
                        // Каратель| Выстрел | Убил мафию | 7 |
                        if ($user['user']['character'] == 'N') {
                            $this->getRating(7, $game_step, 'Каратель| Выстрел | Убил мафию | 7 |');
                            if ($this->params['countNights'] == 1) {
                                $this->getRating(2, $game_step, '+2 за первый ночь');
                            }
                        } elseif ($user['user']['character'] == 'Y') {
                            // Каратель| Выстрел | Убил мирного | 0 |
                            if ($user['user']['role'] == 'Оборотень') {
                                $this->getRating(4, $game_step, 'Каратель| Выстрел | Убил оборотня | 4 |');
                            } else {
                                $this->getRating(0, $game_step, 'Каратель| Выстрел | Убил мирного | 0 |');
                            }
                        } elseif ($user['user']['role'] == 'Оборотень') {
                            // Выстрел | Убил Оборотня (до активации) | 15 |
                            // Выстрел | Убил Оборотня (после активации) | 15 |
                            if ($user['user']['character'] == 'Y') {
                                $this->getRating(4, $game_step, 'Выстрел | Убил Оборотня (до активации) | 4 |');
                            } else {
                                $this->getRating(7, $game_step, 'Выстрел | Убил Оборотня (после активации) | 7 |');
                            }
                        }
                    }
                }
                $game_step->is_confirmed = 1;
                $game_step->save();
                break;
            case 'bomb':
                $game_steps_ = GameStep::where('game_id', $game_step->game_id)
                    ->where('action', 'bomb')
                    ->where('is_confirmed', 0)
                    ->where('from_user_id', $game_step->from_user_id)
                    ->get();
                $mafias_ids = $game_steps_->pluck('to_user_id')->toArray();
                $mfs = Account::whereIn('id', $mafias_ids)->where('character', 'N')->get();
                $counter = count($mfs);

                if ($counter == 1) {
                    $this->getRating(5, $game_step, '| Взрыв | 1 мафия| 5');
                } elseif ($counter == 2) {
                    $this->getRating(12, $game_step, '| Взрыв | 2 мафия| 13');
                } elseif ($counter == 3) {
                    $this->getRating(12, $game_step, '| Взрыв | 3 мафия| 20');
                } elseif ($counter > 4) {
                    $this->getRating(30, $game_step, '| Взрыв | 4 + мафия| 30');
                }
                $game_step->is_confirmed = 1;
                $game_step->save();

                foreach ($game_steps_ as $game_step_) {
                    $game_step_->is_confirmed = 1;
                    $game_step_->save();
                }
                break;







                foreach ($this->params['resumeKillUsers'] as $user) {
                    if ($user['text'] == 'взорван Бомбой' && $user['user']['id'] == $game_step->to_user_id) {
                        if ($user['user']['character'] == 'N') {
                            $counter++;
                        }

                        // //| Взрыв | За каждого убитого мафиози в 1ую ночь | 15 |
                        // if ($user['user']['character'] == 'N' && $this->params['countNights'] == 1) {
                        //     $this->getRating(15, $game_step, '| Взрыв | За каждого убитого мафиози в 1ую ночь | 15 |');
                        // } elseif ($user['user']['character'] == 'N' && $this->params['countNights'] >= 2) {
                        //     //| Взрыв | За каждого убитого мафиози в 2 и тдую ночь | 10 |
                        //     $this->getRating(10, $game_step, '| Взрыв | За каждого убитого мафиози в 2 и тдую ночь | 10 |');
                        // }
                    }
                }


            case 'freddy':
                // 1. Сначала находим самого Фредди в массиве игроков
                $freddy = null;
                foreach ($this->params['users'] as $u) {
                    if ($u['role'] === 'Фредди') {
                        $freddy = $u;
                        break;
                    }
                }

                // 2. Находим цель проверки Фредди (игрок, на ком висит бафф роли 'Фредди')
                $targetUser = null;
                foreach ($this->params['users'] as $u) {
                    if (in_array('Фредди', $u['buff_role'])) {
                        $targetUser = $u;
                        break;
                    }
                }

                // Если не нашли Фредди или цель проверки, прерываем выполнение
                if (!$freddy || !$targetUser) {
                    break;
                }

                // 3. Выполняем проверки на основе статусов Фредди и его цели
                $isFreddyAlive = (int)$freddy['is_alive'] === 1;
                $isTargetMafia = $targetUser['character'] === 'N';

                if ($isFreddyAlive) {
                    // --- СЦЕНАРИИ, КОГДА ФРЕДДИ ЖИВ ---
                    if ($isTargetMafia) {
                        // | Проверка | Нашёл мафию и остался жив | 4 |
                        $this->getRating(4, $game_step, '| Проверка | Нашёл мафию и остался жив| 4 |');
                    } else {
                        // | Проверка | Нашёл мирного и остался жив | 1 |
                        // $this->getRating(1, $game_step, '| Проверка | Нашёл мирного и остался жив| 1 |'); // В логе 1, но в getRating передаем нужный балл (у вас было 4)
                    }

                    $game_step->is_confirmed = 1;
                    $game_step->save();
                    return;
                } else {
                    // --- СЦЕНАРИИ, КОГДА ФРЕДДИ МЕРТВ ---
                    if ($isTargetMafia) {
                        // | Смерть | Убит мафией, последняя проверка — мафия | +15 |
                        $this->getRating(3, $game_step, '| Смерть | Убит мафией, последняя проверка — мафия | +3 |');
                        $game_step->is_confirmed = 1;
                        $game_step->save();

                        // Создаем следующий шаг игры
                        GameStep::create([
                            'game_id'      => $this->params['game']['id'],
                            'from_user_id' => $game_step->from_user_id,
                            'to_user_id'   => $game_step->to_user_id,
                            'action'       => 'freddy',
                            'step'         => $this->step,
                        ]);
                        return;
                    }
                }

                break;


            case 'journalist':
                // 1. Получаем НЕПОДТВЕРЖДЕННЫЕ шаги журналиста для этой игры
                $game_steps = GameStep::where('action', 'journalist')
                    ->where('game_id', $this->game['id']) // Важно привязаться к текущей игре
                    ->where('is_confirmed', 0)
                    ->get();

                // Журналист проверяет по 2 игрока. Если пришел только 1 шаг, ждем второй и ничего не делаем
                if ($game_steps->count() < 2) {
                    break;
                }

                // Берём первые два шага для сравнения
                $step1 = $game_steps[0];
                $step2 = $game_steps[1];

                $first_user_id = $step1->to_user_id;
                $second_user_id = $step2->to_user_id;

                $first_user = null;
                $second_user = null;

                // Ищем пользователей в параметрах
                foreach ($this->params['users'] as $user) {
                    if ($user['id'] == $first_user_id) {
                        $first_user = $user;
                    }
                    if ($user['id'] == $second_user_id) {
                        $second_user = $user;
                    }
                }

                // Если кого-то не нашли, выходим во избежание ошибок
                if (!$first_user || !$second_user) {
                    break;
                }

                // 2. НАЧИСЛЕНИЕ РЕЙТИНГА (Сработает 1 раз, привязываем к $step1)
                if ($first_user['character'] == 'N' && $second_user['character'] == 'N') {
                    $this->getRating(7, $step1, '| Сравнение | Пара «мафия-мафия» | 10 |');

                    // --- ЛОГИКА ДОСТИЖЕНИЙ (выполняется только при мафия-мафия) ---

                    // Создаем запись в GameStep (технический шаг для подсчета пар мафий в будущем)
                    GameStep::create([
                        'game_id' => $this->game['id'],
                        'from_user_id' => $step1->from_user_id, // Используем явный ID из шага
                        'to_user_id' => $step1->to_user_id,
                        'action' => 'journalist',
                        'status' => '',
                        'take_rating_where_true' => 0,
                        'take_rating_where_false' => 0,
                        'role' => 'journalist',
                        'step' => $this->step,
                        'is_confirmed' => 1, // Сразу подтверждаем технический шаг, чтобы он не зацикливал логику
                    ]);

                    // Считаем, сколько ВСЕГО успешных пар мафий нашёл этот журналист
                    $total_mafia_pairs = GameStep::where('action', 'journalist')
                        ->where('from_user_id', $step1->from_user_id)
                        ->count();

                    // Считаем, сколько пар мафий он нашёл конкретно в ЭТОЙ игре
                    $game_mafia_pairs = GameStep::where('action', 'journalist')
                        ->where('game_id', $this->game['id'])
                        ->where('from_user_id', $step1->from_user_id)
                        ->count();

                    // Проверка ачивок
                    if ($game_mafia_pairs >= 2) {
                        Achivment::firstOrCreate([
                            'account_id' => $step1->from_user_id,
                            'text' => 'Разрушитель планов: за журналиста нашел 2 пары играющих за мафию в одной команде, без учета испачканных, за ОДНУ игру.',
                            'description' => 'Моя статья никогда не была просто заметкой. Она была обвинительным заключением. Сколько раз моё раскрытие связей между двумя «незнакомцами» заканчивалось не сенсацией, а арестом. Вернее, казнью. Я строил для города лестницу из фактов, по которой правосудие поднимало врагов к виселице.',
                        ]);
                    }

                    if ($total_mafia_pairs >= 10) {
                        Achivment::firstOrCreate([
                            'account_id' => $step1->from_user_id,
                            'text' => 'Сканер сговора: за журналиста нашел 10 пар играющих за мафию в одной команде,  без учета испачканных.',
                            'description' => 'Они создавали легенды: случайные встречи, старую вражду, неловкое молчание. Я находил одну и ту же нить, связывающую их в темноте. Сколько пар. Сколько красивых лживых сказок, разбитых одним результатом проверки: «Одна команда». Сказкам конец. Я борюсь за правду.',
                        ]);
                    }

                    if ($total_mafia_pairs >= 30) {
                        Achivment::firstOrCreate([
                            'account_id' => $step1->from_user_id,
                            'text' => 'Летописец: за Журналиста установить принадлежность к командам мафии для 30 пар игроков (все пары проверенных в одной команде мафии)',
                            'description' => '30 раз ты распутывал нити в нужных узлах. 30 раз смотрел на двух, казалось бы, незнакомых людей и видел между ними невидимую связь. Одну на двоих. Тайну. Ложь. Кровь. Твой блокнот стал толще. Твои записи — точнее. Город привык к тому, что по утрам ты выходишь на площадь с новыми фактами, новыми именами, новыми разоблачениями. Мафия пыталась подкупить тебя. Угрожать тебе. Убить тебя. Но ты продолжал писать. Потому что знал: правда сильнее пули. Сильнее денег. Сильнее страха. 30 пар. 60 человек. 60 судеб, которые ты связал одной цепью. Теперь твоё имя стало нарицательным. "Смотри, идёт Летописец" — говорят в городе. И мафия прячет лица.',
                        ]);
                    }
                } elseif ($first_user['character'] == 'Y' && $second_user['character'] == 'Y') {
                    $this->getRating(5, $step1, '| Сравнение | Пара «мирный-мирный» | 5 |');
                } elseif (($first_user['character'] == 'N' && $second_user['character'] == 'Y') || ($first_user['character'] == 'Y' && $second_user['character'] == 'N')) {
                    $this->getRating(5, $step1, '| Сравнение | Пара «разные команды» | 5 |');
                }

                // 3. ПОДТВЕРЖДАЕМ ОБА ШАГА ИЗ ПАРЫ, чтобы этот код больше не вызывался для них
                $step1->is_confirmed = 1;
                $step1->save();

                $step2->is_confirmed = 1;
                $step2->save();

                break;


            case 'detective':
                foreach ($this->params['resumeKillUsers'] as $user) {
                    if ($user['text'] == 'убит Детективом') {
                        //| Убийство | Убил проверенную мафию | 10 |

                        GameStep::create([
                            'game_id' => $this->game['id'],
                            'from_user_id' => $game_step->from_user->id,
                            'to_user_id' => $game_step->to_user->id,
                            'action' => 'detective',
                            'status' => '',
                            'take_rating_where_true' => 10, // если убил
                            'take_rating_where_false' => 0, // если не убил
                            'role' => 'Детектив',
                            'step' => $this->step,
                        ]);
                        $game_steps = GameStep::where('from_user_id', $game_step->from_user->id)
                            ->where('action', 'detective')
                            ->get();
                        if (count($game_steps) >= 10) {
                            Achivment::create([
                                'account_id' => $game_step->from_user_id,
                                'text' => 'Судья без мантии: за детектива убил 10 членов мафии',
                                'description' => 'Они думали, что ночь принадлежит им. Что темнота скроет их следы, их лица, их преступления. Снова и снова. Сколько раз ты находил их в толпе. Сколько раз смотрел в глаза, полные лжи. Столько же раз твоя рука не дрожала, когда наступал момент истины. Одни говорят, что ты безжалостен. Другие — что ты справедлив. Но все знают одно: когда Детектив выходит на охоту, мафия перестаёт спать спокойно. Твоё имя стало проклятием для преступного мира. И каждый новый труп мафиози — это не просто цифра. Это шаг к миру, где правда наконец-то будет значить больше, чем ложь.',
                            ]);
                        }

                        if (count($game_steps) >= 30) {
                            Achivment::create([
                                'account_id' => $game_step->from_user_id,
                                'text' => 'Смертный приговор без суда и следствия: за детектива убил 30 членов мафии;',
                                'description' => '30. Три десятка. Тридцать душ, решивших, что закон — это просто слово. Тридцать раз ты смотрел в глаза тем, кто привык убивать, не глядя. Тридцать раз твоя рука не дрогнула, вынося приговор. Одни говорят, что ты маньяк. Другие — что ты герой. Но правда в том, что ты просто не умеешь проходить мимо лжи. Каждый твой выстрел — это не месть. Это урок. Урок для тех, кто думает, что можно безнаказанно красть жизни. Ты не считаешь. Ты просто делаешь то, что должен. Но город считает. Город помнит каждую твою ночь. И когда мафия слышит твоё имя, она перестает дышать. Потому что за тобой — 30 причин бояться.',
                            ]);
                        }

                        $game_steps = GameStep::where('from_user_id', $game_step->from_user->id)
                            ->where('game_id', $this->game['id'])
                            ->where('action', 'detective')
                            ->get();
                        if (count($game_steps) >= 10) {
                            Achivment::create([
                                'account_id' => $game_step->from_user_id,
                                'text' => 'Судья без мантии: за детектива убил 10 членов мафии',
                                'description' => 'Они думали, что ночь принадлежит им. Что темнота скроет их следы, их лица, их преступления. Снова и снова. Сколько раз ты находил их в толпе. Сколько раз смотрел в глаза, полные лжи. Столько же раз твоя рука не дрожала, когда наступал момент истины. Одни говорят, что ты безжалостен. Другие — что ты справедлив. Но все знают одно: когда Детектив выходит на охоту, мафия перестаёт спать спокойно. Твоё имя стало проклятием для преступного мира. И каждый новый труп мафиози — это не просто цифра. Это шаг к миру, где правда наконец-то будет значить больше, чем ложь.',
                            ]);
                        }


                        $this->getRating(10, $game_step, '| Убийство | Убил проверенную мафию | 10 |');
                        if ($user['user']['role'] == 'Дон') {
                            Achivment::create([
                                'account_id' => $game_step->from_user_id,
                                'text' => 'Разрушитель легенд: за Детектива разоблачить и убить Дона мафии.',
                                'description' => 'Он был тенью. Тем, кто дёргал за ниточки, оставаясь невидимым. Его имя никогда не произносили вслух — боялись, что стены имеют уши. Но ты пришёл. Ты видел то, что скрыто за масками. Ты слышал то, что тонет в шёпоте лжи. Две ночи ты следил за ним. Проверял, перепроверял, искал ошибку. Но ошибки не было. Он был тем, кого ты искал. И когда наступил момент истины, ты не дрогнул. Выстрел. Тишина. Легенда умерла. Но город вздохнул свободно. Впервые за долгие годы.',
                            ]);
                        }
                    }
                }
                $game_step->is_confirmed = 1;
                $game_step->save();
                break;
            case 'prispeshnick':
                break;
            case 'don':
                foreach ($this->params['resumeKillUsers'] as $user) {
                    if ($user['text'] == 'убит мафией' && $user['user']['role'] == 'Ооборотень') {
                        Achivment::create([
                            'account_id' => $game_step->from_user_id,
                            'text' => 'Огонь по своим: заблокировал/а и убил/а Оборотня',
                            'description' => 'Ты проснулся. Почувствовал силу. Потянулся, чтобы убить... и… смог. Ты просто убивал мирных, как обычно. А он, гад, оказался Оборотнем. Неприятная неожиданность. Сам виноват.',
                        ]);
                    }
                }
                $game_step->is_confirmed = 1;
                $game_step->save();
                break;
            case 'strelochnik':

                if (isset($this->params['saveNightGame']['arrIdsPlayerKillTheStrelochnik']) && is_array($this->params['saveNightGame']['arrIdsPlayerKillTheStrelochnik']) && count($this->params['saveNightGame']['arrIdsPlayerKillTheStrelochnik']) > 0) {
                    if ($game_step->to_user_id == $this->params['saveNightGame']['arrIdsPlayerKillTheStrelochnik'][0]) {
                        //| Перенаправление | В мафию (убийство)/Оборотня | 20 |

                        foreach ($this->params['resumeKillUsers'] as $user) {

                            if ($user['user']['id'] == $this->params['saveNightGame']['arrIdsPlayerKillTheStrelochnik'][0]) {

                                if ($user['user']['character'] == 'N') {
                                    $this->getRating(15, $game_step, '| Перенаправление | В мафию (убийство) | 15 |');
                                } else {
                                    $this->getRating(0, $game_step, '| Перенаправление | В мирного | 0');
                                }
                            }
                        }
                    }
                }

                break;
        }







        // а теперь в самом конце проверяем стрелочника. Он не записан в GameStep
        //| Перенаправление | В мафию (убийство)/Оборотня | 20 |



        // if ($game_step->role == 'Бомба') {
        //     $this->checkBomb($game_step);
        // }

        // if($game_step->role == 'Детектив') {
        //     $this->cheсkDetective($game_step);
        // }
    }


    public function checkBomb($game_step)
    {
        foreach ($this->killed_users as $user) {

            if ($user->text == 'взорван Бомбой' && $user->user->character == 'N') {
                //даем 15 рейтинга за каждого взорванного мафиози в первую ночь
                if ($this->game->count_nights == 1) {
                    $this->getRating(15);
                }
                //даем 10 рейтинга за каждого взорванного мафиози во вторую ночь
                if ($this->game->count_nights == 2) {
                    $this->getRating(10);
                }
            }
        }
    }

    public function cheсkJournalist($game_step)
    {
        $first_check = null;
        $second_check = null;
        foreach ($this->users as $user) {
            //прогнали первого, остался в цикле второй
            if (in_array('Журналист', $user->buff_role)) {
                $first_check = $user->character;
                continue;
            }
            //дошли до второго
            if (in_array('Журналист', $user->buff_role)) {
                $second_check = $user->character;
                continue;
            }
        }

        if ($first_check == 'N' && $second_check == 'N') {
            //| Сравнение | Пара «мафия-мафия» | 12 |
            $this->getRating(12);
        } elseif ($first_check == 'N' && $second_check == 'Y' || $first_check == 'Y' && $second_check == 'N') {
            //| Сравнение | Пара «разные команды» | 7 |
            $this->getRating(7);
        } elseif ($first_check == 'Y' && $second_check == 'Y') {
            //| Сравнение | Пара «Мирный-Мирный» | 12 |
            $this->getRating(3);
        }
    }

    public function cheсkDetective($game_step)
    {
        //| Убийство | Убил проверенную мафию | 10 |
        if (in_array('Детектив-убийца', $this->to_user->buff_role)) {
            $this->getRating(10);
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

    public function checkForAchivments()
    {


        //Цель №1
        if ($this->params['countNights'] == 1 && $this->params['whatDay'] == 'Ночь') {
            foreach ($this->params['resumeKillUsers'] as $user) {
                if ($user['user']['role'] == 'Шериф') {
                    Achivment::create([
                        'account_id' => $user['user']['id'],
                        'text' => '«Цель №1»: шериф, которого убили в первую ночь',
                        'description' => 'Я вышел на след. Я был так близок! Моя первая проверка — и вот он, результат! Я уже предвкушал, как завтра мы свершим правосудие... А сегодня ночью они свершили правосудие надо мной. Эпилог: я — мученик правды. Но без финальной речи. И вообще, очень мёртвый.',
                    ]);
                }
            }
        }







        //Хранитель Жизней
        $healed_by_doctor_id = $this->params['saveNightGame']['idPlayerTherapyTheDoctor'];
        $doctor_id = null;
        foreach ($this->params['users'] as $user) {
            if ($user['role'] == 'Доктор') {
                $doctor_id = $user['id'];
            }
        }
        foreach ($this->params['resumeKillUsers'] as $user) {
            if (isset($uesr['id']) && $user['id'] == $healed_by_doctor_id) {
                TempAchivment::create([
                    'game_id' => $this->game['id'],
                    'from_user_id' => $doctor_id,
                    'to_user_id' => $user['user']['id'],
                    'achivment' => 'lives_saver',
                    'time_of_day' => $this->params['whatDay'],
                    'count_nights' => $this->params['countNights']
                ]);
                $temp_achivments = TempAchivment::where('game_id', $this->game['id'])
                    ->where('from_user_id', $doctor_id)
                    ->where('achivment', 'lives_saver')
                    ->get();



                if (count($temp_achivments) >= 2) {
                    Achivment::create([
                        'account_id' => $doctor_id,
                        'text' => 'Хранитель Жизней: за доктора верно вылечил 10 мирных от выстрела мафии или Палача.',
                        'description' => 'Десять раз смерть протягивала руку к чьему-то плечу. Десять раз ты успевал раньше. Неважно, была ли это пуля мафии или выстрел Карателя — ты оказывался там, где нужно, в ту самую секунду. Твои руки помнят каждое спасение. Твоё сердце — каждый благодарный взгляд. Город не знает твоего лица, но знает: пока ты дышишь, есть шанс дожить до утра. Ты — Хранитель Жизней. Десять историй, которые продолжились благодаря тебе. И это только начало.',
                    ]);
                }
            }

            //Доктор-эгоист
            $temp_achivments = TempAchivment::where('game_id', $this->game['id'])
                ->where('from_user_id', $doctor_id)
                ->where('to_user_id', $doctor_id)
                ->where('game_id', $this->game['id'])
                ->where('count_nights', '>=', 2)
                ->where('achivment', 'lives_saver')
                ->get();

            if (count($temp_achivments) >= 2) {
                Achivment::create([
                    'account_id' => $doctor_id,
                    'text' => '“Доктор-эгоист” – доктор, лечивший самого себя в первые 2 ночи, когда мафия стреляла в другого игрока.',
                    'description' => 'Первая ночь. Мафия целится в соседа. Ты слышишь шорох, спуск курка… и ты тут же лечишь себя. «На всякий случай», — думаешь ты. Вторая ночь. Повторяется то же самое. Ты снова выбираешь себя. Кто-то умирает, кто-то стонет, а ты в тепле и безопасности. Интересно, сколько за свою карьеру ты поступал так в первые две ночи? Город называет тебя эгоистом. Мафия — благодетелем. А ты просто знаешь: мертвый доктор не спасёт никого. Но живой — может хотя бы спасти себя. Два раза. И ни капли сомнения.',
                ]);
            }

            //Десница
            foreach ($this->params['resumeKillUsers'] as $user) {
                if (isset($user['id']) && $user['id'] == $healed_by_doctor_id) {
                    TempAchivment::create([
                        'game_id' => $this->game['id'],
                        'from_user_id' => $doctor_id,
                        'to_user_id' => $user['user']['id'],
                        'achivment' => 'desnica'
                    ]);
                    $temp_achivments = TempAchivment::where('game_id', $this->game['id'])
                        ->where('from_user_id', $doctor_id)
                        ->where('achivment', 'desnica')
                        ->get();

                    if (count($temp_achivments) >= 3) {
                        Achivment::create([
                            'account_id' => $doctor_id,
                            'text' => 'Десница Бога: доктором вылечил за одну игру 3 раза',
                            'description' => 'Одна ночь. Другая. Третья. Каждый раз, когда смерть протягивала руку, ты успевал первым. Три разные жизни. Три спасения. Трижды город выдыхал с облегчением, а мафия скрежетала зубами. Ты не просто доктор. Ты — чудо. Тот, кто бросает вызов самой судьбе и побеждает. Твои руки дрожат после третьего раза, но в глазах тех, кто остался жив, ты видишь не благодарность — благоговение. В эту ночь ты был не человеком. Ты был десницей Бога. И он не промахнулся ни разу',
                        ]);
                    }
                }
            }
        }
    }
}
