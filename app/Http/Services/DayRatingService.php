<?php

namespace App\Http\Services;

use App\Models\GameStep;



class DayRatingService
{
    public $from_user;
    public $to_user;
    public $step;
    public function __construct($from_user, $to_user, $step)
    {
        $this->from_user = $from_user;
        $this->to_user = $to_user;
        $this->route();
    }


    public function route()
    {

        if ($this->from_user->character == 'Y' && $this->to_user->role == 'Оборотень') {

            // | Мирный | Оборотень (до активации) | Любой исход | 2 |
            // | Мирный | Оборотень (после активации) | Любой исход | 7 |
            $this->civillianToWerewolf();
        } elseif ($this->from_user->character == 'Y' && $this->to_user->character == 'Y' && $this->to_user->role == 'Мирный') {
            // | Мирный | Мирный (без роли) | Любой исход | 2 |
            $this->civillianToCivillianNoRole();
        } elseif ($this->from_user->character == 'Y' && $this->to_user->character == 'Y' && $this->to_user->role != 'Мирный') {
            // | Мирный | Мирный (ролевой) | Любой исход | 0 |
            $this->civillianToCivillianWithRole();
        } elseif ($this->from_user->character == 'Y' && $this->to_user->character == 'N') {
            // Ждем исхода
            // | Мирный | Мафия | Не казнена | 7 |
            // | Мирный | Мафия | Казнена | 10 |
            $this->civillianToMafia();
        } elseif ($this->from_user->character == 'N' && $this->to_user->character == 'N') {
            // | Мафия | Мафия | Любой исход | 0 |
            $this->mafiaToMafia();
        } elseif ($this->from_user->character == 'N' && $this->to_user->character == 'Y' && $this->to_user->role == 'Мирный') {
            // Ждем исхода
            //| Мафия | Мирный (без роли) | Не казнён | 3 |
            //| Мафия | Мирный (без роли) | Казнён | 7 |
            $this->mafiaToCivillianNoRole();
        } elseif ($this->from_user->character == 'N' && $this->to_user->character == 'Y' && $this->to_user->role != 'Мирный') {
            // Ждем исхода
            //| Мафия | Мирный (ролевой) | Не казнён | 3 |
            //| Мафия | Мирный (ролевой) | Казнён | 7 |
            $this->mafiaToCivillianWithRole();
        }
    }

    public function getRating($rating, $text = null)
    {

        $this->from_user->rating += $rating;
        $this->from_user->save();
        file_put_contents('rating-log.txt', 'Дали рейтинга для ' . $this->from_user->name . ', количество : ' . $rating . ' за действие ' . $text . '' . "\n", FILE_APPEND);
        return 'Дали рейтинга игроку:' . $rating;
    }

    public function civillianToCivillianNoRole()
    {

        $this->getRating(1, 'мирный в мирного без роли');
    }

    public function civillianToCivillianWithRole()
    {
        $this->getRating(0);
    }

    public function civillianToMafia()
    {


        // тут мы создаем в БД запись, так как ждем исхода и пока не начисляем рейтинг
        GameStep::create([
           'game_id' => $this->from_user->game_id,
            'table_id' => $this->from_user->table_id,
            'from_user_id' => $this->from_user->id,
            'to_user_id' => $this->to_user->id,
            'action' => 'civillianToMafia',
            'status' => 'Мирный проголосвал в мафию',
            'take_rating_where_true' => 5, // если казнен
            'take_rating_where_false' => 3, // если не казнен
            'text' => 'Мирный проголосвал в мафию',
            'step' => $this->step,
        ]);
    }

    public function civillianToWerewolf()
    {


        if ($this->to_user->character == 'Y') {
            // Если ороротень не активировался (мирный) любой исход
            $this->getRating(2, 'мирный в оборотня');
        } else {
            // Если ороротень активировался (стал мафией) любой исход
            $this->getRating(5, 'мирный в оборотня');
        }
        // тут мы создаем в БД запись, так как ждем исхода и пока не начисляем рейтинг
        // GameStep::create([
        //     // // 'game_id' => $this->from_user->game_id,,,
        //     'from_user_id' => $this->from_user->id,
        //     'to_user_id' => $this->to_user->id,
        //     'action' => 'CivillianToWerewol',
        //     'status' => 'Оборотень ждет активации',
        //     'take_rating_where_true' => 7, // если активирован
        //     'take_rating_where_false' => 2 // если не активирован
        // ]);
    }

    public function mafiaToMafia()
    {
        $this->getRating(0);
    }

    public function mafiaToCivillianNoRole()
    {
        GameStep::create([
            'game_id' => $this->from_user->game_id,
            'table_id' => $this->from_user->table_id,
            'from_user_id' => $this->from_user->id,
            'to_user_id' => $this->to_user->id,
            'action' => 'mafiaToCivillianNoRole',
            'status' => 'Ждет исхода (казнен / не казнен)',
            'take_rating_where_true' => 3, // если казнен
            'take_rating_where_false' => 2, // если не казнен
            'text' => 'Мафия в мирного без роли',
            'step' => $this->step,
        ]);
    }

    public function mafiaToCivillianWithRole()
    {
        GameStep::create([

            'table_id' => $this->from_user->table_id,
            'game_id' => $this->from_user->game_id,
            'from_user_id' => $this->from_user->id,
            'to_user_id' => $this->to_user->id,
            'action' => 'mafiaToCivillianWithRole',
            'status' => 'Ждет исхода (казнен / не казнен)',
            'take_rating_where_true' => 3, // если казнен
            'take_rating_where_false' => 2, // если не казнен
            'text' => 'Мафия в мирного с ролью',
            'step' => $this->step,
        ]);
    }

    public function mafiaToWerewolf()
    {
        GameStep::create([
            'game_id' => $this->from_user->game_id,
            'from_user_id' => $this->from_user->id,
            'to_user_id' => $this->to_user->id,
            'table_id' => $this->from_user->table_id,
            'action' => 'mafiaToYerewolf',
            'status' => 'Ждет активации оборотня',
            'take_rating_where_true' => 0, // если казнен
            'take_rating_where_false' => 3, // если не казнен
            'text' => 'Мафия в оборотня',
            'step' => $this->step,
        ]);
    }
}
