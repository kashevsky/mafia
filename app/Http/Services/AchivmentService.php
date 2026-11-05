<?php

namespace App\Http\Services;

use App\Models\Achivment;
use App\Models\TempAchivment;
use App\Models\TempComformist;
use App\Models\TempSaboter;


class AchivmentService
{
    public $from_user;
    public $to_user;
    public $game;
    public function __construct($from_user, $to_user, $game)
    {
        $this->from_user = $from_user;
        $this->to_user = $to_user;
        $this->game = $game;
        $this->game['id'] = 1;
        $this->handle();
    }

    public function handle()
    {
        if ($this->from_user->character == 'Y') {
            $this->civilianRoute();
        }
    }

    public function civilianRoute()
    {
        //Несгибаемый конформист – мирным голосовал 3 дня за одного того же мирного игрока в одной игре.
        if ($this->to_user->character == 'Y') {
            TempAchivment::create([
                'from_user_id' => $this->from_user->id,
                'to_user_id' => $this->to_user->id,
                'game_id' => 1,
                'time_of_day' => $this->game['whatDay'],
                'achivment' => 'comformist'
            ]);

            $temp_comformists = TempAchivment::where('from_user_id', $this->from_user->id)
                ->where('game_id', $this->game['id'])
                ->where('to_user_id', $this->to_user->id)
                ->where('time_of_day', $this->game['whatDay'])
                ->where('achivment', 'comformist')
                ->get();

            if (count($temp_comformists) >= 3) {
                $achivment = Achivment::where('account_id', $this->from_user->id)
                    ->where('text', 'Несгибаемый конформист - мирным голосовал 3 дня за одного того же мирного игрока в одной игре.')
                    ->first();
                if (!$achivment) {
                    Achivment::create([
                        'account_id' => $this->from_user->id,
                        'text' => 'Несгибаемый конформист - мирным голосовал 3 дня за одного того же мирного игрока в одной игре.',
                        'description' => '«За выдающиеся заслуги в области игнорирования фактов, очевидных остальным игрокам, и беспрецедентную лояльность собственной ошибочной догадке». Ну и ладно! Зато ты был последовательным. А в нашем мире это дорогого стоит.'
                    ]);
                }
                foreach ($temp_comformists as $temp_comformist) {
                    $temp_comformist->delete();
                }
            }
        }



        //● Гений саботажа: будучи мирным голосовал в роль Бомбы и она днем взорвалась, забрав с собой 2 и более мирных..

        if ($this->to_user->role == 'Бомба') {
            TempAchivment::create([
                'from_user_id' => $this->from_user->id,
                'to_user_id' => $this->to_user->id,
                'game_id' => $this->game['id'],
                'achivment' => 'saboter'

            ]);
        }
    }


}
