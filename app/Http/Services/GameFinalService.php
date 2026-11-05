<?php

namespace App\Http\Services;

use App\Models\Account;
use App\Models\AccountGame;
use App\Models\Achivment;
use App\Models\GameStep;
use App\Models\TempSaboter;



class GameFinalService
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
        $this->step = $step;
        $this->users = $params['users'];
        $this->game = $params;
        $this->game['id'] = $this->users[0]['game_id'];
        $ids = array_column(array_column($params['resumeKillUsers'], 'user'), 'id');
        $this->killed_users = Account::whereIn('id', $ids)->get();
        $this->handle();

    }

    public function handle()
    {

        $account_games = AccountGame::where('game_id', $this->game['id'])->get();
        $killed_users_ids = $this->killed_users->pluck('id')->toArray();
        $killed_accounts = AccountGame::whereIn('iaccount_id', $killed_users_ids)->get();
        foreach ($killed_accounts as $killed_account) {
            $killed_account->is_die = true;
            $killed_account->die_in = $this->game['whatDay'];
            $killed_account->die_in_step = $this->game['step'];

            if ($this->game['whatDay'] == 'День') {
                $killed_account->die_in_step = $this->game['countDays'];
            } else {
                $killed_account->die_in_step = $this->game['countNights'];
            }
            $killed_account->save();
        }
    }
}
