<?php

namespace App\Console\Commands;


use App\Http\Services\MafiaImageService;
use App\Http\Services\TelegramService;
use App\Models\Account;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\GameStep;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тест команды с параметрами игры';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $accounts = Account::get();
        // file_put_contents('public/rating-log.txt', '');
        // $gs= GameStep::get();
        // foreach ($gs as $g) {
        //     $g->delete();
        // }

        foreach ($accounts as $account) {

            $account->rating = 0;
            $account->telegram_id = null;
            $account->save();
            // $game_results = GameResult::get();
            // foreach ($game_results as $game_result) {
            //     $game_result->delete();
            //     $account->save();
            // }
        }
    }
}
