<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountGame;
use App\Models\Admin;
use App\Models\City;
use App\Models\Club;
use App\Models\ClubVideo;
use App\Models\Game;
use App\Models\GamePhoto;
use App\Models\Merch;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        $cities = City::get();
        foreach ($cities as $city) {
            $city->delete();
        }

        City::create([
            'title' => 'Москва',
        ]);
        City::create([
            'title' => 'Санкт-Петербург',
        ]);

        $clubs = Club::get();
        foreach ($clubs as $club) {
            $club->delete();
        }

        Club::create([
            'title' => 'Московский клуб',
            'avatar' => '/uploads/clubs/moscow.png',
            'description' => 'Московский клуб',
            'city_id' => City::where('title', 'Москва')->first()->id,
            'totem' => 'Волки',
            'address' => 'г. Москва, ул. Пушкинская, д. 12'
        ]);
        Club::create([
            'title' => 'Санкт-Петербургский клуб',
            'avatar' => '/uploads/clubs/spb.png',
            'totem' => 'Лисы',
            'description' => 'Санкт-Петербургский клуб',
            'city_id' => City::where('title', 'Санкт-Петербург')->first()->id,
            'address' => 'г. Санкт-Петербург, ул. Пушкинская, д. 12'
        ]);

        $clubs = Club::get();
        foreach ($clubs as $club) {
            $club->description =
                '
            <h2>Добро пожаловать в клуб Мафия!</h2>
<p>Мы — сообщество любителей психологической игры "Мафия". У нас вы найдете регулярные игры, турниры, обучение для новичков и дружескую атмосферу.</p>
<br>
<p>Наш клуб существует с 2020 года и объединяет более 200 активных игроков. Мы проводим игры каждую неделю, организуем чемпионаты и мастер-классы от опытных ведущих.</p>

            ';
            ClubVideo::create([
                'title' => 'Ждем вас в клубе!',
                'src' => '/uploads/videos/1.mp4',
                'preview' => '/uploads/events/m2.jpg',
                'club_id' => $club->id
            ]);
            $club->save();
        }

        $games = Game::get();
        foreach ($games as $game) {
            $game->delete();
        }

        Game::create([
            'title' => 'Тренировочная игра',
            'description' => '
            <p>Приглашаем на вечер психологической игры «Мафия» в уютной атмосфере нашего клуба. Игра подойдёт как новичкам, так и опытным игрокам.</p>
<p>
В программе:
</p>
<ul>
<li>3 раунда игры с разными сценариями</li>
<li>Разбор ошибок от профессионального ведущего</li>
<li>Кофе‑брейк</li>
<li>Неформальное общение</li>
</ul>
            ',
            'date' => '2026-04-30',
            'time' => '19:00 - 22:00',
            'club_id' => Club::where('title', 'Московский клуб')->first()->id,
            'avatar' => '/uploads/events/m2.jpg',
            'address' => 'г. Москва, ул. Пушкинская, д. 12',
        ]);
        Game::create([
            'title' => 'Игра для новичков',
             'address' => 'г. Москва, ул. Пушкинская, д. 12',
            'description' => '
            <p>Приглашаем на вечер психологической игры «Мафия» в уютной атмосфере нашего клуба. Игра подойдёт как новичкам, так и опытным игрокам.</p>
<p>
В программе:
</p>
<ul>
<li>3 раунда игры с разными сценариями</li>
<li>Разбор ошибок от профессионального ведущего</li>
<li>Кофе‑брейк</li>
<li>Неформальное общение</li>
</ul>
            ',
            'date' => '2026-04-20',
            'time' => '19:00 - 22:00',
            'club_id' => Club::where('title', 'Московский клуб')->first()->id,
            'avatar' => '/uploads/events/m3.jpg',
        ]);
        Game::create([
            'title' => 'Турнирная игра',
             'address' => 'г. Москва, ул. Пушкинская, д. 12',
            'description' => '
            <p>Приглашаем на вечер психологической игры «Мафия» в уютной атмосфере нашего клуба. Игра подойдёт как новичкам, так и опытным игрокам.</p>
<p>
В программе:
</p>
<ul>
<li>3 раунда игры с разными сценариями</li>
<li>Разбор ошибок от профессионального ведущего</li>
<li>Кофе‑брейк</li>
<li>Неформальное общение</li>
</ul>
            ',
            'date' => '2026-04-19',
            'time' => '19:00 - 22:00',
            'club_id' => Club::where('title', 'Московский клуб')->first()->id,
            'avatar' => '/uploads/events/m4.jpg',
        ]);
        Game::create([
            'title' => 'Игра с ведущими',
             'address' => 'г. Москва, ул. Пушкинская, д. 12',
            'description' => '
            <p>Приглашаем на вечер психологической игры «Мафия» в уютной атмосфере нашего клуба. Игра подойдёт как новичкам, так и опытным игрокам.</p>
<p>
В программе:
</p>
<ul>
<li>3 раунда игры с разными сценариями</li>
<li>Разбор ошибок от профессионального ведущего</li>
<li>Кофе‑брейк</li>
<li>Неформальное общение</li>
</ul>
            ',
            'date' => '2026-04-19',
            'time' => '19:00 - 22:00',
            'club_id' => Club::where('title', 'Санкт-Петербургский клуб')->first()->id,
            'avatar' => '/uploads/events/v1.jpg',
        ]);
        Game::create([
            'title' => 'Весенний турнир - День 1',
             'address' => 'г. Москва, ул. Пушкинская, д. 12',
            'description' => '
            <p>Приглашаем на вечер психологической игры «Мафия» в уютной атмосфере нашего клуба. Игра подойдёт как новичкам, так и опытным игрокам.</p>
<p>
В программе:
</p>
<ul>
<li>3 раунда игры с разными сценариями</li>
<li>Разбор ошибок от профессионального ведущего</li>
<li>Кофе‑брейк</li>
<li>Неформальное общение</li>
</ul>
            ',
            'date' => '2026-05-22',
            'time' => '19:00 - 22:00',
            'club_id' => Club::where('title', 'Санкт-Петербургский клуб')->first()->id,
            'avatar' => '/uploads/events/v2.jpg',
        ]);

        $photos = GamePhoto::get();
        foreach ($photos as $photo) {
            $photo->delete();
        }

        $games = Game::get();
        foreach ($games as $game) {

            GamePhoto::create([
                'game_id' => $game->id,
                'src' => '/uploads/events/m1.webp',
            ]);
            GamePhoto::create([
                'game_id' => $game->id,
                'src' => '/uploads/events/m2.jpg',
            ]);
            GamePhoto::create([
                'game_id' => $game->id,
                'src' => '/uploads/events/m3.jpg',
            ]);
            GamePhoto::create([
                'game_id' => $game->id,
                'src' => '/uploads/events/m4.jpg',
            ]);
        }

        $admins = Admin::get();
        foreach ($admins as $admin) {
            $admin->delete();
        }

        Admin::create([
            'name' => 'superadmin',
            'password' => 'superadmin',
            'role' => 'superadmin',
            'is_main_admin' => 1
        ]);



        $merches = Merch::get();


        foreach ($merches as $merch) {
            $merch->delete();
        }

        Merch::create([
            'title' => 'Футболка "ФОКС"',
            'src' => '/images/store/fut.png',
            'price' => '250',
            'description' => 'Чёрная футболка с логотипом клуба',
        ]);
        Merch::create([
            'title' => 'Кружка "ФОКС"',
            'src' => '/images/store/cup.png',
            'price' => '300',
            'description' => 'Кружка с логотипом клуба фокс',
        ]);
        Merch::create([
            'title' => 'Шоппер "ФОКС"',
            'src' => '/images/store/sho.png',
            'price' => '350',
            'description' => 'Шоппер с логотипом клуба фокс',
        ]);
        $accounts = Account::all();
        foreach ($accounts as $account) {
            $account->delete();
        }
        $users = [
            [
                'name' => 'Чебурашка',
                'number' => 1,
                'arr_id_vote' => '',
                'character' => 'Y',
                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 1,
                'role' => 'Судья',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
            [
                'name' => 'Гена',
                'number' => 2,
                'arr_id_vote' => '',

                'character' => 'Y',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 2,
                'role' => 'Шериф',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
            [
                'name' => 'Шапокляк',
                'number' => 3,
                'arr_id_vote' => '',

                'character' => 'Y',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 3,
                'role' => 'Детектив',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
            [
                'name' => 'Буратино',
                'number' => 4,
                'arr_id_vote' => '',

                'character' => 'Y',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 4,
                'role' => 'Бомба',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
            [
                'name' => 'Арлекин',
                'number' => 5,
                'arr_id_vote' => '',

                'character' => 'N',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 5,
                'role' => 'Мафия',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
            [
                'name' => 'Мальвина',
                'number' => 6,
                'arr_id_vote' => '',

                'character' => 'N',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 6,
                'role' => 'Дон',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
            [
                'name' => 'Папа Карло',
                'number' => 7,
                'arr_id_vote' => '',

                'character' => 'N',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 7,
                'role' => 'Приспешник',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
            [
                'name' => 'Колобок',
                'number' => 8,
                'arr_id_vote' => '',

                'character' => 'N',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 8,
                'role' => 'Шулер',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
            [
                'name' => 'Айболит',
                'number' => 9,
                'arr_id_vote' => '',

                'character' => 'Y',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 9,
                'role' => 'Доктор',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
            [
                'name' => 'Вини-пух',
                'number' => 10,
                'arr_id_vote' => '',

                'character' => 'Y',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 10,
                'role' => 'Адвокат',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
            [
                'name' => 'Пятачок',
                'number' => 11,
                'arr_id_vote' => '',

                'character' => 'Y',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 11,
                'role' => 'Каратель',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
            [
                'name' => 'Терминатор',
                'number' => 12,
                'arr_id_vote' => '',

                'character' => 'Y',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 12,
                'role' => 'Фредди',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
            [
                'name' => 'Шапокляк 2',
                'number' => 13,
                'arr_id_vote' => '',

                'character' => 'Y',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 13,
                'role' => 'Журналист',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
            [
                'name' => 'Шапокляк 3',
                'number' => 14,
                'arr_id_vote' => '',

                'character' => 'Y',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 14,
                'role' => 'Стрелочник',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',
                'avatar' => '',
            ],
            [
                'name' => 'Оскар',
                'number' => 15,
                'arr_id_vote' => '',

                'character' => 'Y',


                'is_alive' => 1,
                'is_vote' => 0,
                'position' => 15,
                'role' => 'Оборотень',
                'start_game' => 0,
                'target' => 0,
                'nickname' => '',
                'password' => '',
                'email' => '',
                'phone' => '',


                'avatar' => '',
            ],
        ];

        $game = Game::first();
        $clubs = Club::get();
        foreach ($users as $userData) {
            $a = Account::create($userData);
            $a->club_id = $clubs->random()->id;
            $a->save();
            AccountGame::create([
                'account_id' => Account::latest()->first()->id,
                'game_id' => $game->id,
            ]);
        }
    }
}
