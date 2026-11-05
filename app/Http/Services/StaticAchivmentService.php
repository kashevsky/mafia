<?php

namespace App\Http\Services;

use App\Models\Achivment;
use App\Models\TempAchivment;
use App\Models\TempBlind;
use App\Models\TempComformist;
use App\Models\TempDetector;
use App\Models\TempFigurant;
use App\Models\TempGarant;
use App\Models\TempLoudRazoblachenie;
use App\Models\TempPacient;
use App\Models\TempSaboter;
use App\Models\TempTargetNumberOne;

class StaticAchivmentService
{


    public static function checkForFigurant($to_user, $from_user, $params)
    {
        //проверка на постоянного фигуранта
        TempAchivment::create([
            'game_id' => $from_user->game_id,
            'from_user_id' => $from_user->id,
            'to_user_id' => $to_user->id,
            'achivment' => 'figurant'
        ]);
        $temp_figurants = TempAchivment::where('to_user_id', $to_user->id)
            ->where('achivment', 'figurant')
            ->get();
        if (count($temp_figurants) >= 30) {
            Achivment::create([
                'account_id' => $to_user->id,
                'text' => 'Постоянный фигурант: 30 раз лишали права на дневном суде',
                'description' => 'Тридцать раз судья поднимал молоток, и твой голос исчезал. Тридцать раз ты становился безмолвным наблюдателем, когда город вершил суд. Но тишина — не всегда поражение. Иногда она — школа. Ты научился говорить так, что слова летели дальше голосов. Твои аргументы запоминали, твои сомнения сеяли зерна, твои обвинения преследовали мафию даже без твоего голоса. Ты — подсудимый, которого не смогли засудить. Тридцать раз. И ты всё ещё здесь.'
            ]);
            foreach ($temp_figurants as $temp_figurant) {
                $temp_figurant->delete();
            }
        }
    }


    public static function checkForPacient($to_user, $from_user, $params)
    {

        TempAchivment::create([
            'game_id' => $from_user->game_id,
            'from_user_id' => $from_user->id,
            'to_user_id' => $to_user->id,
            'achivment' => 'pacient'
        ]);
        $temp_pacients = TempAchivment::where('to_user_id', $to_user->id)
            ->where('achivment', 'pacient')
            ->get();
        if (count($temp_pacients) >= 30) {
            Achivment::create([
                'account_id' => $to_user->id,
                'text' => 'Важный пациент: 30 раз был вылечен Доктором',
                'description' => 'Тридцать раз смерть стояла у твоего изголовья. Тридцать раз чья-то рука останавливала её, возвращая тебя в игру. Ты не знаешь имени этого человека. Ты не видел его лица. Но каждую ночь, когда пуля уже летела в твоё сердце, кто-то шептал: "Не сегодня". Ты стал легендой среди пациентов — тем, кого не берут ни нож, ни яд, ни пуля. Врачи спорят о тебе, мафия проклинает, а ты просто просыпаешься каждое утро и идёшь голосовать. Твоя жизнь — не везение. Это чья-то воля. И ты её оправдал. Тридцать раз.',
            ]);
            foreach ($temp_pacients as $temp_figurant) {
                $temp_figurant->delete();
            }
        }
    }



    //"Слепой" служитель закона: играя за Шерифа, три проверки за игру показывал на мирного жителя.
    public static function checkForBlind($to_user, $from_user, $params)
    {

        if ($to_user->character == 'Y') {
            TempAchivment::create([
                'game_id' => $from_user->game_id,
                'from_user_id' => $from_user->id,
                'to_user_id' => $to_user->id,
                'achivment' => 'blind'
            ]);
            $temp_blinds = TempAchivment::where('from_user_id', $from_user->id)
                ->where('achivment', 'blind')
                ->where('game_id', $from_user->game_id)
                ->get();

            if (count($temp_blinds) >= 3) {
                Achivment::create([
                    'account_id' => $from_user->id,
                    'text' => '"Слепой" служитель закона: играя за Шерифа, три проверки за игру показывал на мирного жителя.',
                    'description' => 'Моя звезда сияет! Мой взгляд сосредоточен! Моя подозрительность зашкаливает! Три ночи кряду я срывал маски с главных угроз городу. Один оказался мирным. Другой — мирным. Третий — мной в прошлой жизни. Но я не сдаюсь! Мафия, я всё ещё ищу тебя! Где ты прячешься?! (Желательно не прямо у меня под носом).',
                ]);
                foreach ($temp_blinds as $temp_blind) {
                    $temp_blind->delete();
                }
            }
        }
    }


    public static function checkForLoudRazoblachenie($to_user, $from_user, $params)
    {

        if ($params['whatDay'] == 'Ночь' && $params['countNights'] == 1 && $to_user->character == 'N') {

            TempAchivment::create([
                'game_id' => $from_user->game_id,
                'from_user_id' => $from_user->id,
                'to_user_id' => $to_user->id,
                'achivment' => 'loudRazoblachenie'
            ]);
            $temp_razoblachenies = TempAchivment::where('from_user_id', $from_user->id)
                ->where('achivment', 'loudRazoblachenie')
                ->get();

            if (count($temp_razoblachenies) >= 5) {
                Achivment::create([
                    'account_id' => $from_user->id,
                    'text' => '«Громкое разоблачение»: играя за шерифа, 5 раз проверил мафию в первую же ночь.',
                    'description' => 'Пока город спит, а мафия строит планы, я уже на дежурстве. Первая ночь — не лотерея. Это первое задержание. Моя звезда загорается раньше утренней зари, отмечая того, кто думал, что тень — его союзник. Я не жду улик. Я создаю их своим взглядом.'
                ]);
                foreach ($temp_razoblachenies as $temp_razoblachenie) {
                    $temp_razoblachenie->delete();
                }
            }
        }
    }


    public static function checkForDetector($to_user, $from_user, $params)
    {

        if ($to_user->character == 'N') {

            TempAchivment::create([
                'game_id' => $from_user->game_id,
                'from_user_id' => $from_user->id,
                'to_user_id' => $to_user->id,
                'achivment' => 'detector'
            ]);
            $temp_detectors = TempAchivment::where('from_user_id', $from_user->id)
                ->where('achivment', 'detector')
                ->get();

            if (count($temp_detectors) >= 10) {
                Achivment::create([
                    'account_id' => $from_user->id,
                    'text' => 'Детектор лжи: за шерифа верно проверил 10 мафий.',
                    'description' => 'Я вышел в свет, надел звезду на грудь и ждал. Враги искали меня, как и расчёт, но я был начеку. Теперь у меня не только положительный результат. У меня — список тех, кто слишком живо интересовался моей смертью и верил в свою безнаказанность',
                ]);
                foreach ($temp_detectors as $temp_detector) {
                    $temp_detector->delete();
                }
            }
        }
    }

    public static function checkForGarant($to_user, $from_user, $params)
    {

        if ($to_user->character == 'N') {

            TempAchivment::create([
                'game_id' => $from_user->game_id,
                'from_user_id' => $from_user->id,
                'to_user_id' => $to_user->id,
                'achivment' => 'garant'
            ]);
            $temp_garants = TempAchivment::where('from_user_id', $from_user->id)

                ->where('achivment', 'garant')
                ->get();

            if (count($temp_garants) >= 50) {
                Achivment::create([
                    'account_id' => $from_user->id,
                    'text' => 'Гарант безопасности: за шерифа верно проверил 50 мафий.',
                    'description' => 'Они искали Шерифа в громких речах и уверенных взглядах. Я был тихим, спокойным, одним из многих. А ночью мой взгляд выжигал их тайны десятки раз. Они проиграли, так и не узнав, чей взгляд стал для них зеркалом. Лучшая тактика для охотника — стать невидимкой и давать другим охотникам свет. Я оставлял клеймо на лбу лжи. Город может спать. Шериф — не спит.',
                ]);
                foreach ($temp_garants as $temp_garant) {
                    $temp_garant->delete();
                }
            }
        }
    }

    public static function checkForDoctorHaos($to_user, $from_user, $params)
    {

        if ($to_user->character == 'N') {

            TempAchivment::create([
                'game_id' => $to_user->game_id,
                'from_user_id' => $from_user->id,
                'to_user_id' => $to_user->id,
                'achivment' => 'doctorHaos'
            ]);
            $temp_doctorHaos = TempAchivment::where('from_user_id', $from_user->id)
                ->where('achivment', 'doctorHaos')
                ->where('game_id', $from_user->game_id)
                ->get();

            if (count($temp_doctorHaos) >= 2) {
                Achivment::create([
                    'account_id' => $from_user->id,
                    'text' => 'Доктор ХаОс: играя за Доктора, 2 ночи лечил мафию в одной игре.',
                    'description' => 'Клятва Гиппократа? Не, не слышал. Вроде бы поклялся спасать жизни. Говорил, что твоё призвание — защищать невиновных. Но почему-то каждую ночь лечение доставалось тем, кто наутро смотрел на тебя с хитрой улыбкой. Две ночи подряд ты пытался вытащить с того света мафиози. Твоя клятва городу дала трещину. Ты не доктор. Ты — спасатель мафии. В местной криминальной хронике тебя прозвали «Доктор ХаОс». И, кажется, тебе это даже нравится',
                ]);
                foreach ($temp_doctorHaos as $temp_doctorHaos) {
                    $temp_doctorHaos->delete();
                }
            }
        }
    }

    public static function checkForAngel($to_user, $from_user, $params)
    {

        $healed_by_doctor_id = $params['saveNightGame']['idPlayerTherapyTheDoctor'];
        $doctor_id = null;
        foreach ($params['users'] as $user) {
            if ($user['role'] == 'Доктор') {
                $doctor_id = $user['id'];
            }
        }
        foreach ($params['resumeKillUsers'] as $user) {
            if ($user['id'] == $healed_by_doctor_id && $from_user->character == 'Y') {
                TempAchivment::create([
                    'game_id' => $from_user->game_id,
                    'from_user_id' => $from_user->id,
                    'to_user_id' => $to_user->id,
                    'achivment' => 'angel'
                ]);
                $temp_angel = TempAchivment::where('from_user_id', $from_user->id)
                    ->where('achivment', 'angel')
                    ->where('game_id', $from_user->game_id)
                    ->get();
                if (count($temp_angel) >= 5) {
                    Achivment::create([
                        'account_id' => $from_user->id,
                        'text' => 'Ангел-Хранитель: Доктором вылечил 50 мирных жителей.',
                        'description' => 'Они падали. Снова и снова. Но я был рядом — с надеждой, с упорством, граничащим с безумием. 50 раз я вырывал их из лап смерти. 50 раз говорил: "Дыши. Ты нужен этому городу". Мои руки помнят тепло каждой спасённой жизни. Мое сердце бьётся в такт с их сердцами. В городе, где каждый может стать жертвой, я остаюсь тем, кто отказывается это принимать. Я не просто лечу. Я верю. И этой веры хватает на всех.',
                    ]);
                    foreach ($temp_angel as $temp_angel) {
                        $temp_angel->delete();
                    }
                }
            }
        }
    }

    public static function checkForMasterOtsrochcki($params)
    {



    }
}
