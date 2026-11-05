<?php

namespace App\Http\Services;

use App\Models\Account;
use App\Models\Achivment;
use App\Models\Game;
use App\Models\GameStep;
use App\Models\Table;
use App\Models\TempAchivment;
use App\Models\TempSaboter;



class FinishService
{
    public $game;
    public $users;
    public $winers_team;
    public $users_collection;
    public $step;

    public function __construct($users, $winers_team_, $step)
    {


        $this->users = $users;
        $this->step = $step;
        $users_ids = collect($users)->pluck('id');
        $this->users_collection = Account::whereIn('id', $users_ids)->get();
        $this->winers_team = $winers_team_;
        $this->getFinalrating($users);
        $this->handle();
    }


    public function handle()
    {
        $now = now();
        Account::upsert(
            array_map(fn($user) => [
                'id' => (int) $user['id'],
                'is_alive' => (int) $user['is_alive'],
                'created_at' => $now,
                'updated_at' => $now,
            ], $this->users),
            ['id'],
            ['is_alive', 'updated_at']
        );

        switch ($this->winers_team) {
            case 'мафия':

                $this->mafiaWin();
                break;
            case 'мирные':
                $this->civillianWin();
                break;
            case 'ничья':
                $this->draw();
                break;
        }




        foreach ($this->users as $user) {
            $acc = Account::where('id', $user['id'])->first();
            if ($user['is_alive'] == 1 && $user['role'] != 'Мирный' && $user['role'] != 'Мафия') {
                // $acc->rating += 2;
                // file_put_contents('rating-log.txt', 'Дали рейтинга для ' . $user['name'] . ', количество : 2 за действие ' . 'Выживание (без роли)' . '' . "\n", FILE_APPEND);
                // $acc->save();
            }
            if ($user['character'] == 'Y') {
                $achivment = Achivment::where('account_id', $user['id'])
                    ->where('text', 'Новорождённый горожанин:  в самую первую игру в Федерации получил роль мирного игрока.')
                    ->first();
                $achivment_2 = Achivment::where('account_id', $user['id'])
                    ->where('text', 'Дитя мафии:  в самую первую игру получил карту мафии.')
                    ->first();
                if (!$achivment && !$achivment_2) {
                    Achivment::create([
                        'account_id' => $user['id'],
                        'text' => 'Новорождённый горожанин:  в самую первую игру в Федерации получил роль мирного игрока.',
                        'description' => 'Ты сделал первый вдох в этом городе. Ещё не знаешь, кто врёт, а кто боится. Ещё не умеешь прятать глаза за улыбкой. Но ты уже здесь. Ты уже за столом. Ты уже часть истории. Первая роль — самая честная. Добро пожаловать в Федерацию обмана Ф.О.К.С.',
                    ]);
                }
            } else {
                $achivment_2 = Achivment::where('account_id', $user['id'])
                    ->where('text', 'Новорождённый горожанин:  в самую первую игру в Федерации получил роль мирного игрока.')
                    ->first();
                $achivment = Achivment::where('account_id', $user['id'])
                    ->where('text', 'Дитя мафии:  в самую первую игру получил карту мафии.')
                    ->first();
                if (!$achivment && !$achivment_2) {
                    Achivment::create([
                        'account_id' => $user['id'],
                        'text' => 'Дитя мафии:  в самую первую игру получил карту мафии.',
                        'description' => 'Ты пришёл в этот город наивным и чистым. Думал, будешь мирным жителем, будешь искать правду, голосовать за справедливость. А судьба... судьба рассмеялась тебе в лицо и сунула в руки карту мафии. Первая игра. Первая роль. Первая ложь. Ты даже не знал, как держать карты, а уже должен был убивать. Трясущимися руками ты выбирал жертву. Сбивчивым голосом оправдывался днём. Это был твой первый вечер в мире, где правда и ложь поменялись местами. Дитя мафии. Ты родился в тот вечер заново. И обратной дороги нет. Добро пожаловать в Федерацию обмана Ф.О.К.С.',
                    ]);
                }
            }


            $game_steps = GameStep::where('to_user_id', $user['id'])
                ->where('text', 'Реально мирный: 7 игр подряд сыграть за мирного без роли.')
                ->get();
            if ($user['character'] == 'Y' && $user['role'] == 'Мирный') {
                if (count($game_steps) >= 7) {
                    Achivment::create([
                        'account_id' => $user['id'],
                        'text' => 'Реально мирный: 7 игр подряд сыграть за мирного без роли.',
                        'description' => 'Ты сделал 7 игр подряд сыграть за мирного без роли.',
                        'description' => '',
                    ]);
                } else {
                    GameStep::create([
                        'to_user_id' => $user['id'],
                        'text' => 'Реально мирный: 7 игр подряд сыграть за мирного без роли.',
                        'step' => $this->step,
                    ]);
                }
            } else {
                foreach ($game_steps as $game_step) {
                    $game_step->delete();
                }
            }



            $game_steps = GameStep::where('to_user_id', $user['id'])
                ->where('text', 'Уважаемый мафиози: 5 побед за мафию подряд, без поражений.')
                ->get();
            if ($user['character'] == 'N' && $this->winers_team == 'мафия') {

                if (count($game_steps) >= 4) {
                    Achivment::create([
                        'account_id' => $user['id'],
                        'text' => 'Уважаемый мафиози: 5 побед за мафию подряд, без поражений.',
                        'description' => 'Пять игр. Пять раздач ты получал карту мафии. Пять раз город просыпался в трауре. Пять раз мафия поднимала бокалы, пока мирные хоронили своих. Ты планировал. Ты убивал. Ни одной ошибки. Ни одного сомнения. Ни одного проигрыша. В криминальном мире это называют "идеальная серия". Твоё имя теперь произносят с уважением даже те, кто тебя никогда не видел. Дон, Шулер, Приспешник, рядовой мафиози — неважно. Важно другое: когда ты за столом, мафия побеждает. Пять серий подряд. Это не везение. Это мастерство. Это стиль. Это легенда. Уважаемый мафиози — так теперь тебя называют. И в этом звании слышен страх города и гордость твоих.',
                    ]);
                } else {
                    GameStep::create([
                        'to_user_id' => $user['id'],
                        'text' => 'Уважаемый мафиози: 5 побед за мафию подряд, без поражений.',
                        'step' => $this->step,
                    ]);
                }
            } else {
                foreach ($game_steps as $game_step) {
                    $game_step->delete();
                }
            }



            $game_steps = GameStep::where('to_user_id', $user['id'])
                ->where('text', 'Победить в команде мафии 10 раз, оставшись живым.')
                ->get();
            if ($user['character'] == 'N' && $this->winers_team == 'мафия' && $user['is_alive'] == 1) {

                if (count($game_steps) >= 9) {
                    Achivment::create([
                        'account_id' => $user['id'],
                        'text' => 'Победить в команде мафии 10 раз, оставшись живым.',
                        'description' => '',
                    ]);
                } else {
                    GameStep::create([
                        'to_user_id' => $user['id'],
                        'text' => 'Победить в команде мафии 10 раз, оставшись живым.',
                        'step' => $this->step,
                    ]);
                }
            } else {
                foreach ($game_steps as $game_step) {
                    $game_step->delete();
                }
            }






            $table = Table::find($this->users[0]['table_id']);
            if (!$table->winners == 'мафия') {
                $table->winners = 'Y';
            } elseif (!$table->winners == 'мирные') {
                $table->winners = 'YN';
            } else {
                $table->winners = 'N';
            }

            $table->save();
        }
    }

    public function mafiaWin()
    {
        foreach ($this->users as $user) {

            if ($user['character'] == 'N') {
                $user = Account::find($user['id']);
                $user->rating += 10;
                file_put_contents('rating-log.txt', 'Дали рейтинга для ' . $user->name . ', количество : 10 за действие ' . 'Мафия победила' . '' . "\n", FILE_APPEND);
                $user->save();
                if ($user['is_alive'] == 1) {
                    // $user = Account::find($user['id']);
                    // $user->rating += 15;
                    // file_put_contents('rating-log.txt', 'Дали рейтинга для ' . $user->name . ', количество : 15 за действие ' . 'Мафия победила, жиовой' . '' . "\n", FILE_APPEND);
                    // $user->save();
                    $game_steps = GameStep::where('to_user_id', $user['id'])
                        ->where('game_id', $user['game_id'])
                        ->where('char', 'nev_ruck')
                        ->get();
                    if (count($game_steps) == 0) {
                        Achivment::create([
                            'account_id' => $user['id'],
                            'text' => 'Невидимая рука: Победить за мафию, ни разу не собрав максимум голосов в дневное голосование (не быть в топе подозреваемых).',
                            'description' => 'Весь день город кричал. Они искали врага, тыкали пальцами, строили теории. Кто-то ошибался, кто-то догадывался, кто-то просто молчал. А ты... ты просто сидел среди них. Улыбался. Пожимал плечами. Иногда говорил: "Да, действительно, странный этот игрок". И никто — слышишь? — НИКТО не посмотрел в твою сторону. Ты был тенью. Фоновым шумом. Человеком-невидимкой. А ночью ты убивал. Методично, хладнокровно, безжалостно. И когда город пал, когда последний мирный закрыл глаза, они так и не поняли, кто это сделал. Ты не просто мафия. Ты — Невидимая рука. Та, что движет всем, оставаясь незаметной.',
                        ]);
                    }
                    foreach ($game_steps as $game_step) {
                        $game_step->delete();
                    }
                } else {
                    // $user = Account::find($user['id']);
                    // $user->rating += 5;
                    // file_put_contents('rating-log.txt', 'Дали рейтинга для ' . $user->name . ', количество : 5 за действие ' . 'Мафия победила, умерший' . '' . "\n", FILE_APPEND);
                    // $user->save();
                }
            }
            if ($user['character'] == 'N' && $user['is_alive'] == 1 && $user['role'] == 'Оборотень') {
                //!!! ОБОРОТЕНЬ ПОБЕДИЛ !!!
                $user = Account::find($user['id']);
                $user->rating += 10;
                $user->save();
                file_put_contents('rating-log.txt', 'Дали рейтинга для ' . $user->name . ', количество : 10 за действие ' . 'Оборотень победил' . '' . "\n", FILE_APPEND);
                $table = Table::find($this->users[0]['table_id']);
                $table->is_mafia_win = 2;
                $table->save();
                GameStep::create([
                    'account_id' => $user['id'],
                    'game_id' => $this->users[0]['game_id'],
                    'table_id' => $this->users[0]['table_id'],
                    'char' => 'odin_korol',
                    'step' => $this->step,
                ]);

                $game_steps = GameStep::where('account_id', $user['id'])
                    ->where('char', 'odin_korol')
                    ->get();
                if (count($game_steps) == 3) {
                    Achivment::create([
                        'account_id' => $user['id'],
                        'text' => 'Одинокий Король: одержал 3 победы за оборотня.',
                        'table_id' => $this->users[0]['table_id'],
                        'game_id' => $this->users[0]['game_id'],
                        'description' => 'Они играли в команды. В дружбу. В доверие. Я играл в выживание. Пять раз я доказывал, что в этой игре нет союзников — есть только ресурсы и помехи. Мафия была моим инструментом. Мирные — моим прикрытием. А финальная ночь — моим законным правом. Гора трупов. И один я на вершине. Это не жестокость. Это — естественный отбор.',
                    ]);
                }
                $game_steps = GameStep::where('to_user_id', $user['id'])
                    ->where('game_id', $this->game->id)
                    ->where('char', 'master_intrigi')
                    ->get();

                if (count($game_steps) > 0) {
                    Achivment::create([
                        'account_id' => $user['id'],
                        'text' => 'Мастер интриги: сыграл/а за Оборотня так, что был/а проверен Шерифом, Детективом, или Журналистом и впоследствии выиграл/а игру.',
                        'table_id' => $this->users[0]['table_id'],
                        'game_id' => $this->users[0]['game_id'],
                        'description' => 'Они направляли на меня взоры, детекторы лжи и перекрестные опросы. И каждый раз их приборы показывали одно и то же: «Мирный». Я стал гением их слепых зон. Моя легенда была настолько безупречной, что даже правда, вооружённая до зубов, проходила мимо. Я выиграл не потому, что меня не проверяли. Я выиграл потому, что даже проверки стали моими союзниками, укрепляя моё алиби. Самая надёжная маскировка — это быть записанным в протокол как невиновный.',
                    ]);
                }

                $game_steps = GameStep::where('to_user_id', $user['id'])
                    ->where('game_id', $this->game->id)
                    ->where('char', 'paradoks')
                    ->get();
                if (count($game_steps) == 0) {
                    Achivment::create([
                        'account_id' => $user['id'],
                        'text' => 'Ходячий парадокс: Одержал победу, будучи Оборотнем, при этом ни разу не убив самостоятельно.',
                        'table_id' => $this->users[0]['table_id'],
                        'game_id' => $this->users[0]['game_id'],
                        'description' => 'Зачем пачкать когти, когда можно пачкать чужие репутации? Я не наносил ударов. Я сеял семена подозрения и поливал их страхом. Они убивали друг друга за меня, оправдывая это правосудием, местью или глупостью. Я лишь направлял шепотом: «Посмотри на него. Разве он не виновен?». И они смотрели. И находили. И казнили. Моя победа чиста. На ней нет ни капли моей крови. Зато есть отпечатки их пальцев на ножах.',
                    ]);
                }
            }
        }
    }



    public function civillianWin()
    {

        foreach ($this->users as $user) {
            if ($user['character'] == 'Y') {
                if ($user['is_alive'] == 1) { // живой
                    if ($user['role'] != 'Мирный') { // не ролевой
                        $user = Account::find($user['id']);
                        $user->rating += 15;
                        file_put_contents('rating-log.txt', 'Дали рейтинга для ' . $user->name . ', количество : 15 за действие ' . 'Мирные победили. Не ролевой. Живой. ' . '' . "\n", FILE_APPEND);
                        $user->save();
                    } else { // ролевой
                        $user = Account::find($user['id']);
                        $user->rating += 10;
                        file_put_contents('rating-log.txt', 'Дали рейтинга для ' . $user->name . ', количество : 10 за действие ' . 'Мирные победили. Ролевой. Живой. ' . '' . "\n", FILE_APPEND);
                        $user->save();
                    }

                    if ($user['role'] == 'Каратель') {
                        GameStep::create([
                            'account_id' => $user['id'],
                            'game_id' => $this->users[0]['game_id'],
                            'table_id' => $this->users[0]['table_id'],
                            'char' => 'master_mascirovki',
                            'step' => $this->step,
                        ]);
                        $game_steps = GameStep::where('account_id', $user['id'])
                            ->where('char', 'master_mascirovki')
                            ->get();
                        if (count($game_steps) == 3) {
                            Achivment::create([
                                'account_id' => $user['id'],
                                'text' => 'Мастер маскировки: Выиграть 3 игры за Ферзя, каждый раз дожив до конца.',
                                'table_id' => $this->users[0]['table_id'],
                                'game_id' => $this->users[0]['game_id'],
                                'description' => 'Прислушиваются к шагам, всматриваются в тени, гадают, кто же из этих мирных лиц на самом деле держит палец на спусковом крючке. А ты просто сидишь среди них. Улыбаешься. Пьёшь чай. Обсуждаешь, кто сегодня мог бы быть мафией. И никто — слышишь? — никто не знает, что именно твоя рука отправила на тот свет уже не одного их подельника. Три игры ты проходил по лезвию. Три раза мафия охотилась еа тебя. Трижды ты оставался в живых, и тогда город ликовал. Ты — тень. Ты — призрак. Ты — тот, кого нельзя поймать. Потому что Каратель не умирает. Каратель ждёт следующей ночи.',
                            ]);
                        }
                    }
                } else { // убит
                    if ($user['role'] != 'Мирный') { // не ролевой
                        $user = Account::find($user['id']);
                        $user->rating += 7;
                        file_put_contents('rating-log.txt', 'Дали рейтинга для ' . $user->name . ', количество : 7 за действие ' . 'Мирные победили. Не ролевой. Убит. ' . '' . "\n", FILE_APPEND);
                        $user->save();
                    } else { // ролевой
                        $user = Account::find($user['id']);
                        $user->rating += 5;
                        file_put_contents('rating-log.txt', 'Дали рейтинга для ' . $user->name . ', количество : 5 за действие ' . 'Мирные победили. Ролевой. Убит. ' . '' . "\n", FILE_APPEND);
                        $user->save();
                    }
                }
            }
        }
    }

    public function draw() {}

    public function getFinalrating($users)
    {
        foreach ($users as $userData) {
            // 1. Проверяем ролевых игроков (НЕ Мирный И НЕ Мафия)
            if ($userData['is_alive'] == 1 && $userData['role'] != 'Мирный' && $userData['role'] != 'Мафия') {

                $account = Account::find($userData['id']);
                if ($account) {
                    $account->rating += 0;
                    $account->save();

                    $logText = 'Дали рейтинга для ' . $account->name . ', количество : 0 за действие Выживание (c ролью)' . "\n";
                    file_put_contents('rating-log.txt', $logText, FILE_APPEND);
                }
            }
            // 2. Проверяем безролевых игроков (Мирный ИЛИ Мафия)
            elseif ($userData['is_alive'] == 1 && ($userData['role'] == 'Мирный' || $userData['role'] == 'Мафия')) {

                $account = Account::find($userData['id']);
                if ($account) {
                    $account->rating += 2;
                    $account->save();

                    // ИСПРАВЛЕНО: Заменено file_get_contents на file_put_contents
                    $logText = 'Дали рейтинга для ' . $account->name . ', количество : 2 за действие Выживание (без роли)' . "\n";
                    file_put_contents('rating-log.txt', $logText, FILE_APPEND);
                }
            }
        }
    }
}
