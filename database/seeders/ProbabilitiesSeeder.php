<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountProbability;
use App\Models\Probability;
use App\Models\ProbabilityRole;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ProbabilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ps =  Probability::get();
        $rols = Role::get();
        foreach ($rols as $role) {
            $role->delete();
        }

        foreach ($ps as $p) {
            $p->delete();
        }

        $aps = AccountProbability::get();

        foreach ($aps as $ap) {
            $ap->delete();
        }

        $don = Role::create([
            'title' => 'Дон',
            'src' => 'rols/maf.png'
        ]);
        $jud = Role::create([
            'title' => 'Судья',
            'src' => 'rols/jud.png'
        ]);
        $doc = Role::create([
            'title' => 'Доктор',
            'src' => 'rols/doc.png'
        ]);
        $maf = Role::create([
            'title' => 'Мафия',
            'src' => 'rols/maf.png'
        ]);
        $obo = Role::create([
            'title' => 'Оборотень',
            'src' => 'rols/obo.png'
        ]);
        $she = Role::create([
            'title' => 'Шериф',
            'src' => 'rols/she.png'
        ]);
        $det = Role::create([
            'title' => 'Детектив',
            'src' => 'rols/det.png'
        ]);
        $jur = Role::create([
            'title' => 'Журналист',
            'src' => 'rols/jur.png'
        ]);
        $fred = Role::create([
            'title' => 'Мститель',
            'src' => 'rols/mst.png'
        ]);

        $kar = Role::create([
            'title' => 'Ферзь',
            'src' => 'rols/kar.png'
        ]);
        $str = Role::create([
            'title' => 'Стрелочник',
            'src' => 'rols/str.png'
        ]);
        $bom = Role::create([
            'title' => 'Бомба',
            'src' => 'rols/bom.png'
        ]);
        $adv = Role::create([
            'title' => 'Адвокат',
            'src' => 'rols/adv.png'
        ]);

        $p = Probability::create([
            'text' => "['Шериф','Детектив','Журналист','Мститель']",
            'price' => 1200,
            'title' => 'Золотая лихорадка',
            'src' => '/images/store/sho.png'
        ]);
        $rols = Role::get();
        foreach ($rols as $role) {
            if ($role->title != 'Мирный' && $role->title != 'Мафия') {
                ProbabilityRole::create([
                    'probability_id' => $p->id,
                    'role_id' => $role->id
                ]);
            }
        }

        $p = Probability::create([
            'text' => "['Шериф','Детектив','Журналист','Мститель']",
            'price' => 700,
            'title' => 'Око Ф.О.К.С.а',
            'src' => '/images/store/sho.png'
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $she->id
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $det->id
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $jur->id
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $fred->id
        ]);
        // ProbabilityRole::create([
        //     'probability_id' => $p->id,
        //     'role_id' => $mir->id
        // ]);

        $p = Probability::create([
            'text' => "['Ферзь','Мститель','Бомба','Стрелочник']",
            'price' => 700,
            'title' => 'Время жатвы',
            'src' => '/images/store/sho.png'
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $kar->id
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $fred->id
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $bom->id
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $str->id
        ]);


        $p = Probability::create([
            'text' => "['Дон','Мафия','Оборотень']",
            'price' => 500,
            'title' => 'Ночной дозор',
            'src' => '/images/store/sho.png'
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $don->id
        ]);

        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $obo->id
        ]);

        $p = Probability::create([
            'text' => "['Бомба','Бомба']",
            'price' => 700,
            'title' => 'Русская рулетка',
            'src' => '/images/store/sho.png'
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $kar->id
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $bom->id
        ]);


        $p = Probability::create([
            'text' => "['Адвокат','Доктор','Стрелочник']",
            'price' => 600,
            'title' => 'Щит и меч',
            'src' => '/images/store/sho.png'
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $adv->id
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $doc->id
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $str->id
        ]);

        $p = Probability::create([
            'text' => "['Адвокат','Доктор','Судья']",
            'price' => 500,
            'title' => 'Закон и порядок',
            'src' => '/images/store/sho.png'
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $adv->id
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $doc->id
        ]);
        ProbabilityRole::create([
            'probability_id' => $p->id,
            'role_id' => $jud->id
        ]);






        $user = Account::first();
        AccountProbability::create([
            'account_id' => $user->id,
            'probability_id' => $p->id
        ]);
    }
}
