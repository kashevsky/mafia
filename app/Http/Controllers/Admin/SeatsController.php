<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AuthHelper;
use App\Models\Application;
use Illuminate\Http\Request;

class SeatsController extends Controller
{
    public function show($id)
    {
        $admin = AuthHelper::getAdmin();
        $game = \App\Models\Game::find($id);

        $account = \App\Models\Account::find(1);
        $applications = Application::with('account')->where('game_id', $id)->get();

        foreach ($applications as $application) {
            $application->nickname = $application->account->nickname;
            $application->avatar = $application->account->avatar;
            $application->nickname = $application->account->nickname;
        }

        $players = $applications;

        // Формируем массив currentSeating: позиция → ID игрока
        $currentSeating = [];
        foreach ($applications as $app) {
            if (!is_null($app->position)) {
                $currentSeating[$app->position] = $app->account_id;
            }
        }

        return view('admin.seats', compact(
            'game',
            'account',
            'players',
            'currentSeating', // Передаём в шаблон
            'admin'
        ));
    }


    public function save(Request $request)
    {
        $params = request()->all();

        $seating = $params['seating'];

        foreach ($seating as $key => $seat) {

            $application = Application::where('account_id', $seat)
                ->where('game_id', $params['game_id'])
                ->first();

            $application->position = $key;
            $application->save();
            $account = $application->account;
            $account->position = $key;
            $account->save();
        }

        return redirect()->back();
    }
}
