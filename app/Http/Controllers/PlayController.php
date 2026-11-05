<?php

namespace App\Http\Controllers;

use App\Http\Helpers\TgHelper;
use App\Http\Services\TelegramService;
use App\Models\Account;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\GameWinner;
use App\Models\PayEntity;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlayController extends Controller
{
    public function index($table_id)
    {

        $table = Table::find($table_id);


        $users = Account::with('immunities')->where('table_id', $table_id)->get();


        $game = Game::find($table->game_id);

        return view('play', compact('users', 'table', 'game'));
    }

    public function modal($table_id = null)
    {

        $table = Table::find($table_id);
        $game = Game::find($table->game_id);

        $payEntities = PayEntity::with('account')->where('game_id', $game->id)->get();

        if ($table_id) {
            $users = Account::where('table_id', $table_id)->get();
        } else {
            $users = Account::get()->take(10);
        }



        return view('modals.payment', compact('users', 'payEntities', 'table', 'game'));
    }


    public function modal2($game_id = null)
    {
        $game = Game::findOrFail($game_id);

        // Получаем ВСЕХ игроков этой игры со всех столов сразу
        $users = Account::where('game_id', $game->id)->get();

        // Получаем все оплаты по этой игре
        $payEntities = PayEntity::with('account')->where('game_id', $game->id)->get();

        // Передаем $table как null, так как мы смотрим общий отчет по игре, а не конкретный стол
        $table = null;

        return view('modals.payment2', compact('users', 'payEntities', 'table', 'game'));
    }


    public function sendPaymentData(Request $request)
    {
        $data = $request->input('tabs', []);

        $tableId = $request->input('tableId', null);

        foreach ($data as $tab) {
            $type = $tab['tabName'] ?? null;

            $players = $tab['players'] ?? [];

            foreach ($players as $player) {
                $accountId = $player['id'] ?? null;
                $amount = $player['amount'] ?? null;

                if (!$accountId || $amount <= 0) {
                    continue;
                }
                $account = Account::find($accountId);
                PayEntity::create([
                    'game_id' => $account->game_id,
                    'account_id' => $accountId,
                    'amount' => $amount,
                    'table_id' => $account->table_id,       // tabId → table_id
                    'type' => $type,            // tabName → type
                    'club_id' => $account->club_id
                ]);
            }
        }
        return response()->json($data);
    }

    public function killPlayer(Request $request)
    {
        $params = $request->all();
        $account = Account::find($params['id']);
        $account->table_id = null;
        $account->save();


        return redirect()->back();
    }

    public function makePublic($game_id)
    {



        $game = Game::findOrFail($game_id);




        // Получаем все результаты игры с аккаунтами
        $results = GameResult::with('account')
            ->where('game_id', $game_id)
            ->get();

        if ($results->isEmpty()) {
            dd('Нет результатов');
        }
        $game->is_published = 1;
        $game->save();

        // Группируем по account_id и считаем итоговые очки (сумма total)
        // values() сбрасывает ключи коллекции в 0, 1, 2... чтобы $index работал как порядковый номер
        $sorted = $results->groupBy('account_id')->map(function ($group, $accountId) {
            $account = $group->first()->account; // все записи имеют одинаковый account

            return [
                'account_id' => $accountId,
                'nickname'   => $account->nickname,
                'total'      => $group->sum('total'), // суммируем все очки игрока
                'is_table'   => (bool)$group->contains(fn($r) => $r->is_table), // true, если хоть одна запись была is_table
            ];
        })->sortByDesc('total')->values();

        // Удаляем старых победителей
        GameWinner::where('game_id', $game_id)->delete();

        $currentRank = 1;
        $previousTotal = null;

        foreach ($sorted as $index => $player) {
            $total = $player['total'];

            // $index теперь гарантированно идет как 0, 1, 2... благодаря ->values() выше
            if ($previousTotal !== null && $total === $previousTotal) {
                $rank = $currentRank;
            } else {
                $currentRank = $index + 1;
                $rank = $currentRank;
            }
            $previousTotal = $total;

            // Создаем записи через стандартный метод модели Eloquent
            GameWinner::create([
                'game_id'    => $game_id,
                'account_id' => $player['account_id'],
                'total'      => $player['total'],
                'position'   => $rank
            ]);
        }

        // Подгружаем только связь 'account'. Сортируем строго по сохраненной позиции в базе
        $winners = GameWinner::with('account')
            ->where('game_id', $game_id)
            ->orderBy('position')
            ->get()
            ->unique('account_id');



        return view('admin.table.public_game', compact('game', 'winners'));
    }

    public function makeOboroten(Request $request)
    {
        $params = $request->all();
        foreach ($params['id'] as $id) {
            $account = Account::find($id);
            $account->character = 'N';
            $account->save();
        }
    }
}
