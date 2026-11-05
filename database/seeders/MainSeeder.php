<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Admin;
use App\Models\Chance;
use App\Models\City;
use App\Models\Club;
use App\Models\ClubVideo;
use App\Models\Game;
use App\Models\GamePhoto;
use App\Models\GameVideo;
use App\Models\Merch;
use Illuminate\Database\Seeder;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $accounts = Account::get();
        foreach ($accounts as $account) {
            $account->delete();
        }

        Account::create([
            'nickname' => 'Kashevsky',
            'password' => '123456',
            'email' => 'kashevsky.d@yandex.ru',
            'phone' => '+375292281015',
            'role' => 'admin',
            'is_admin' => 1,
            'balance' => 1000,
            'avatar' => '/uploads/avatars/dima.jpg',
            'status' => 'active',
            'telegram_id' => '5325830700',
        ]);
        Account::create([
            'nickname' => 'Pups',
            'password' => '123456',
            'email' => 'kashevsky.d2@yandex.ru',
            'phone' => '+375292281015',
            'role' => 'admin',
            'is_admin' => 1,
            'balance' => 1000,
            'avatar' => '/uploads/avatars/pups.png',
            'status' => 'active',
        ]);
        Account::create([
            'nickname' => 'Bulba',
            'password' => '123456',
            'email' => 'kashevsky.d2@yandex.ru',
            'phone' => '+375292281015',
            'role' => 'admin',
            'is_admin' => 1,
            'balance' => 1000,
            'avatar' => '/uploads/avatars/bulba.png',
            'status' => 'active',
        ]);

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
            ClubVideo::create([
                'title' => 'Ждем вас в клубе!',
                'src' => '/uploads/videos/1.mp4',
                'preview' => '/uploads/events/m3.jpg',
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
            'avatar' => '/uploads/events/v1.jpg',
        ]);
        Game::create([
            'title' => 'Игра для новичков',
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
            'avatar' => '/uploads/events/v2.jpg',
        ]);
        Game::create([
            'title' => 'Турнирная игра',
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
            'avatar' => '/uploads/events/v3.jpg',
        ]);
        Game::create([
            'title' => 'Игра с ведущими',
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

        $chances = Chance::get();
        foreach ($chances as $chance) {
            $chance->delete();
        }

        Chance::create([
            'title' => 'Комиссар',
            'description' => 'Увеличить шанс стать комиссаром',
            'price' => '350',
            'src' => '/images/achivments/2.png',

        ]);
        Chance::create([
            'title' => 'Мафия',
            'description' => 'Увеличить шанс выпадения роли мафии',
            'price' => '350',
            'src' => '/images/achivments/2.png',

        ]);
        Chance::create([
            'title' => 'Дон мафии',
            'description' => 'Увеличить шанс бытьдоном мафии',
            'price' => '350',
            'src' => '/images/achivments/2.png',

        ]);

        $accounts = Account::get();
        foreach ($accounts as $account) {
            $account->name = $account->nickname;
            $account->save();
        }
    }
}
