<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AuthHelper;
use App\Http\Services\MafiaRoleService;
use App\Http\Services\TelegramService;
use App\Models\Account;
use App\Models\Application;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\GameWinner;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{

    public function create($id, $is_dop = null)
    {

        $admin = AuthHelper::getAdmin();
        $game = \App\Models\Game::find($id);

        $account = \App\Models\Account::find(1);
        $applications = Application::with('account')->where('game_id', $id)->get();

        foreach ($applications as $application) {
            $application->nickname = $application->account->nickname;
            $application->avatar = $application->account->avatar;
            $application->name = $application->account->name;
        }

        $players = $applications;

        // Формируем массив currentSeating: позиция → ID игрока
        $currentSeating = [];
        $applications = Application::with('account')
            ->where('game_id', $id)

            ->get();
        // foreach ($applications as $app) {
        //     if (!is_null($app->position)) {
        //         $currentSeating[$app->position] = $app->account_id;
        //     }
        // }


        $playerIds = $players->pluck('id');

        $allUsers = Account::whereNotIn('id', $playerIds)->get();

        return view('admin.table.create', compact(
            'game',
            'account',
            'players',
            'currentSeating', // Передаём в шаблон
            'admin',
            'allUsers',
            'is_dop',
        ));
    }

    public function makePublicQuestion($table_id)
    {
        $table = Table::with('game')->findOrFail($table_id);
        $game  = $table->game;

        $currentStep = $table->step;
        $alreadyPublished = false;

        if (GameResult::where([
            'table_id' => $table->id,
            'step'     => $currentStep,
        ])->exists()) {
            $alreadyPublished = true;
        }

        $players = Account::where('table_id', $table->id)->get();
        $resultsBatch = [];

        foreach ($players as $player) {
            $totalScore = max(0, $player->rating - $player->rating_before_game);

            $resultsBatch[] = [
                'account_id'   => $player->id,
                'game_id'      => $game->id,
                'total'        => $totalScore,
                'table_id'     => $table->id,
                'step'         => $currentStep,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        if (!$alreadyPublished) {
            GameResult::insert($resultsBatch);
        }

        $table->save();

        $targetStep = $currentStep;

        $allResults = GameResult::with('account')
            ->where('table_id', $table->id)
            ->where('step', $targetStep)
            ->get();

        if ($allResults->isEmpty()) {
            return view('admin.table.public_question', [
                'table' => $table,
                'game' => $game,
                'mafiaResults' => collect(),
                'peaceResults' => collect(),
                'winnerIsMafia' => false,
                'mafiaTotal' => 0,
                'peaceTotal' => 0,
                'isSnapshot' => false // Обычный показ, кнопку можно оставить
            ]);
        }

        $mafiaResults = $allResults->filter(fn($r) => $r->account->character === 'N')->sortByDesc('total');
        $peaceResults = $allResults->filter(fn($r) => $r->account->character === 'Y')->sortByDesc('total');

        $mafiaTotal = $mafiaResults->sum('total');
        $peaceTotal = $peaceResults->sum('total');
        $winnerIsMafia = $table->is_mafia_win;

        // --- МОДИФИКАЦИЯ ЗДЕСЬ ---

        // Данные для базы данных (БЕЗ кнопки)
        $snapshotData = compact(
            'table',
            'game',
            'mafiaResults',
            'peaceResults',
            'winnerIsMafia',
            'mafiaTotal',
            'peaceTotal'
        );
        $snapshotData['isSnapshot'] = true; // Говорим шаблону СКРЫТЬ кнопку

        // Рендерим чистый HTML для сохранения в базу данных
        $htmlContentForDb = view('admin.table.public_question', $snapshotData)->render();

        // Сохраняем в базу данных чистый снимок без кнопки
        \Illuminate\Support\Facades\DB::table('temp_publics')->updateOrInsert(
            ['table_id' => $table->id, 'step' => $targetStep],
            [
                'snapshot'   => $htmlContentForDb,
                'updated_at' => now(),
            ]
        );

        // Данные для ответа пользователю в браузер (С кнопкой, чтобы он её видел)
        $browserData = compact(
            'table',
            'game',
            'mafiaResults',
            'peaceResults',
            'winnerIsMafia',
            'mafiaTotal',
            'peaceTotal'
        );
        $browserData['isSnapshot'] = false; // Говорим шаблону ПОКАЗАТЬ кнопку

        $htmlContentForBrowser = view('admin.table.public_question', $browserData)->render();

        // Возвращаем пользователю страницу, где кнопка на месте
        return response($htmlContentForBrowser);
    }





    public function save(Request $request)
    {

        $params = request()->all();

        $game = Game::find($params['game_id']);
        $table = Table::create([
            'title' => $params['table_name'] ? $params['table_name'] : 'Стол ' . $params['game_id'] . '/' . rand(10, 999),
            'game_id' => $params['game_id'],
            'total_space' => $params['seats_count'],
            'is_dop' => $params['is_dop'] ? 1 : null,
        ]);


        $table_name = $params['table_name'];
        if (!$table_name) {
            $table_name = 'Table ' . $params['game_id'];
        }
        if ($table->is_dop == 1) {
            $table->title = 'Дополнительная игра, стол: ' . $table->id;
            $table->save();
        }
        if (isset($params['seating'])) {
            $seating = $params['seating'];

            foreach ($seating as $key => $seat) {
                $application = Application::firstOrCreate([
                    'account_id' => $seat,
                    'game_id' => $params['game_id'],
                ]);

                $application->position = $key;
                $application->table_id = $table->id;
                $application->save();
                $account = $application->account;
                $account->position = $key;
                $account->table_id = $table->id;
                $account->save();
            }
            $table->occupied_space = count($seating);
            $table->save();
        }

        return redirect()->route('table.edit', $table->id);
    }


    public function edit($table_id)
    {

        $table = Table::find($table_id);
        $admin = AuthHelper::getAdmin();
        $game = \App\Models\Game::find($table->game_id);

        $account = \App\Models\Account::find(1);
        $applications = Application::with('account')->where('game_id', $game->id)->get();

        foreach ($applications as $application) {
            $application->nickname = $application->account->nickname;
            $application->avatar = $application->account->avatar;
            $application->name = $application->account->name;
        }

        $players = $applications;

        // Формируем массив currentSeating: позиция → ID игрока
        $currentSeating = [];

        $applications = Application::with('account')
            ->where('table_id', $table->id)
            ->get();
        foreach ($applications as $application) {
            $application->nickname = $application->account->nickname;
            $application->avatar = $application->account->avatar;
            $application->name = $application->account->name;
        }
        foreach ($applications as $app) {
            if (!is_null($app->position)) {
                $currentSeating[$app->position] = $app->account_id;
            }
        }

        // Если $players — это коллекция Eloquent-моделей
        $playerIds = $players->pluck('id');

        $allUsers = Account::whereNotIn('id', $playerIds)->get();


        return view('admin.table.edit', compact(
            'game',
            'table',
            'account',
            'players',
            'currentSeating', // Передаём в шаблон
            'admin',
            'allUsers'
        ));
    }

    // public function save(Request $request)
    // {

    //     $params = request()->all();
    //     $game = Game::find($params['game_id']);
    //     $table = Table::create([
    //         'title' => $params['table_name'] ? $params['table_name'] : 'Стол ' . $params['game_id'] . '/' . rand(10, 999),
    //         'game_id' => $params['game_id'],
    //         'total_space' => $params['seats_count'],
    //     ]);


    //     $table_name = $params['table_name'];
    //     if (!$table_name) {
    //         $table_name = 'Table ' . $params['game_id'];
    //     }
    //     if ($game->finished_at) {
    //         $table->is_dop = 1;
    //         $table->title = 'Дополнительная игра, стол: ' . $table->id;
    //         $table->save();
    //     }
    //     if (isset($params['seating'])) {
    //         $seating = $params['seating'];

    //         foreach ($seating as $key => $seat) {

    //             $application = Application::where('account_id', $seat)
    //                 ->where('game_id', $params['game_id'])
    //                 ->first();

    //             $application->position = $key;
    //             $application->table_id = $table->id;
    //             $application->save();
    //             $account = $application->account;
    //             $account->position = $key;
    //             $account->table_id = $table->id;
    //             $account->save();
    //         }
    //         $table->occupied_space = count($seating);
    //         $table->save();
    //     }

    //     return redirect()->route('table.edit', $table->id);
    // }

    public function update(Request $request)
    {
        $params = $request->all();
        $table = Table::find($params['table_id']);

        if (isset($params['table_name'])) {
            $table->title = $params['table_name'];
        }

        $seating = $params['seating'] ?? [];

        // КРИТИЧЕСКИ ВАЖНО: Приводим ID игроков к типу Integer для точного сравнения в БД
        $playerIds = array_map('intval', array_values($seating));

        // Убираем из стола всех, кого нет в новой рассадке
        Application::where('table_id', $table->id)
            ->where('game_id', $params['game_id'])
            ->whereNotIn('account_id', $playerIds)
            ->update(['position' => null, 'table_id' => null]);

        // То же самое для account (+ ОБНУЛЯЕМ РОЛЬ старым игрокам, чтобы они не «призрачничали»)
        \App\Models\Account::where('table_id', $table->id)
            ->whereNotIn('id', $playerIds)
            ->update(['position' => null, 'table_id' => null, 'role' => null]);



        foreach ($seating as $key => $seat) {
            $application = Application::firstOrCreate([
                'account_id' => $seat,
                'game_id' => $params['game_id'],
            ]);

            $application->position = $key;
            $application->table_id = $table->id;
            $application->save();

            $account = $application->account;
            if ($account) {
                $account->position = $key;
                $account->table_id = $table->id;
                // Если игрок сел заново, но на другое место — на всякий случай сбросим роль перед раздачей
                if ($account->isDirty('position')) {
                    $account->role = null;
                }
                $account->save();
            }
        }

        $table->occupied_space = count($seating);
        $table->save();

        return redirect()->back();
    }



    public function delete($id)
    {
        $table = Table::find($id);
        $table->delete();
        return redirect()->back();
    }

    public function run($id)
    {
        $table = Table::find($id);
        $table->is_running = 1;
        $table->save();
        return redirect()->back();
    }

    public function stop($id)
    {
        $table = Table::find($id);
        $table->is_running = 0;
        $table->save();
        return redirect()->back();
    }

    public function randomRoles($table_id)
    {


        $params = request()->all();

        $service = new MafiaRoleService();
        $table = Table::find($table_id);
        $table->is_with_hanchman = 0;
        $table->save();
        if (!$table->step) {
            $table->step = 0;
        }
        $game = Game::find($table->game_id);
        $success = $service->randomRoles($table_id);
        $map = [
            'Дон',
            'Шулер',
            'Приспешник',
            'Мафия'
        ];
        $service = new TelegramService();
        $accounts = Account::where('table_id', $table_id)->get();
        $accounts_count = count($accounts);
        if($accounts_count <= 10){
            $table->count_hanchman = 2;
            $table->save();
        }

        $via_email = $params['via'] ?? false;

        foreach ($accounts as $account) {
            $account->refresh();
            $account->rating_before_game = $account->rating;
            $account->game_id = $game->id;
            if (in_array($account->role, $map)) {
                $account->character = 'N';
            } else {
                $account->character = 'Y';
            }

            if ($account->role == 'Приспешник') {
                $table->is_with_hanchman = 1;
                $account->role = 'Мафия';
            }

            if ($account->role == 'Адвокат') {

                // $account->role = 'Фредди';
            }
            $account->save();
            sleep(0.5);
            if ($via_email) {

                $service->sendRolesViaEmail($account);
            } else {
                $service->sendRoles($account);
            }

        }


        $table->save();

        return redirect()->route('play', $table_id);
    }

    public function randomRolesNextTables($table_id)
    {

        $service = new MafiaRoleService();
        $table = Table::find($table_id);
        $game = Game::find($table->game_id);
        $success = $service->randomRoles($table_id);
        $map = [
            'Дон',
            'Шулер',
            'Приспешник',
            'Мафия'
        ];
        $service = new TelegramService();
        $accounts = Account::where('table_id', $table_id)->get();


        foreach ($accounts as $account) {
            if (in_array($account->role, $map)) {
                $account->character = 'N';
            } else {
                $account->character = 'Y';
            }

            if ($account->role == 'Приспешник') {
                $account->role = 'Мафия';
                $table->is_with_hanchman = 1;
                continue;
            }
            if ($account->role == 'Шулер') {
                $table->is_with_hanchman = 1;
                $account->role = 'Мафия';
                continue;
            }
            $account->save();
            $service->sendRoles($account);
        }

        return redirect()->route('play', $table_id);
    }



    public function randomRoles1($table_id)
    {
        $table = Table::find($table_id);
        $users = Account::with('probabilities')->where('table_id', $table_id)->get();
        // только те, у кого есть связанные записи
        $users_with_probabilities = Account::with('probabilities')
            ->has('probabilities')
            ->where('table_id', $table_id)
            ->get();

        $users_count = count($users);




        //!!

        $users_count = 11;
        //!!
        $roles_arr = $layouts[$users_count];
        $rand_number = rand(1, count($roles_arr));
        $rand_roles = $roles_arr[$rand_number];


        //Роль записывается в поле role

    }
    public function createAfterGame($table_id)
    {

        $table = Table::find($table_id);
        $admin = AuthHelper::getAdmin();
        $game = \App\Models\Game::find($table->game_id);

        $account = \App\Models\Account::find(1);
        $applications = Application::with('account')->where('game_id', $game->id)->get();

        foreach ($applications as $application) {
            $application->nickname = $application->account->nickname;
            $application->avatar = $application->account->avatar;
            $application->name = $application->account->name;
        }

        $players = $applications;

        // Формируем массив currentSeating: позиция → ID игрока
        $currentSeating = [];

        $applications = Application::with('account')
            ->where('table_id', $table->id)
            ->get();
        foreach ($applications as $application) {
            $application->nickname = $application->account->nickname;
            $application->avatar = $application->account->avatar;
            $application->name = $application->account->name;
        }
        foreach ($applications as $app) {
            if (!is_null($app->position)) {
                $currentSeating[$app->position] = $app->account_id;
            }
        }


        return view('admin.table.edit', compact(
            'game',
            'table',
            'account',
            'players',
            'currentSeating', // Передаём в шаблон
            'admin',
            'allUsers'
        ));
    }

    public function showSnapshot($id)
    {
        // Всего один легкий запрос к одной таблице
        $record = \Illuminate\Support\Facades\DB::table('temp_publics')->find($id);

        if (!$record || !$record->snapshot) {
            abort(404, 'Снимок страницы не найден.');
        }

        // Возвращаем чистый готовый HTML напрямую в браузер
        return response($record->snapshot);
    }
}
