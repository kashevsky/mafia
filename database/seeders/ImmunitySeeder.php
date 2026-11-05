<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountImmunity;
use App\Models\Immunity;
use Illuminate\Database\Seeder;

class ImmunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $is = Immunity::get();
        foreach ($is as $i) {
            $i->delete();
        }
        $is =  AccountImmunity::get();
        foreach ($is as $i) {
            $i->delete();
        }
        $a = Immunity::create([
            'type' => 'd/n',
            'title' => 'Иммунитет день/ночь',
            'price' => 1500,
        ]);
        $b = Immunity::create([
            'type' => 'd/n/d',
            'title' => 'Иммунитет день/ночь/день',
            'price' => 2000,
        ]);

        $ar = [$a->id, $b->id];

        $acs = Account::get();
        foreach ($acs as $k=>$ac) {
            if ($k % 2 === 0) {
                AccountImmunity::create([
                    'account_id' => $ac->id,
                    'immunity_id' => $ar[rand(0, 1)],
                    'is_active' => rand(0, 1),
                ]);
            }
        }
    }
}
