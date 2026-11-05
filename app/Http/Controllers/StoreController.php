<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountChance;
use App\Models\AccountImmunity;
use App\Models\AccountMerch;
use App\Models\AccountProbability;
use App\Models\Chance;
use App\Models\Immunity;
use App\Models\Merch;
use App\Models\Probability;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {

        $account = session()->get('account');
        $account = Account::with('club', 'probabilities')->find($account->id);

        $club = $account->club;
        $merch = Merch::all();





        $buyed_merch = AccountMerch::where('account_id', $account->id)->get();



        $purchasedMerchIds = $buyed_merch->pluck('merch_id')->toArray();



        $merchWithStatus = $merch->map(function ($item) use ($purchasedMerchIds) {
            $item->purchased = in_array($item->id, $purchasedMerchIds);
            return $item;
        });
        $merch = $merchWithStatus;

        $chances = Probability::with('roles')->get();
        $buyed_chances = AccountProbability::where('account_id', $account->id)
        ->where('is_activated', 0)
        ->get();

        $activateChancesIds = $buyed_chances->where('is_active', 1)->pluck('probability_id')->toArray();

        $purchasedChanceIds = $buyed_chances->pluck('probability_id')->toArray();

        $chancesWithStatus = $chances->map(function ($item) use ($purchasedChanceIds, $activateChancesIds) {
            $item->purchased = in_array($item->id, $purchasedChanceIds, true);

            return $item;
        });
        $chances = $chancesWithStatus;


        $immunities = Immunity::get();
        $buyed_immunities = AccountImmunity::where('account_id', $account->id)->get();

        $purchasedImmunityIds = $buyed_immunities->pluck('immunity_id')->toArray();
        $activateImmunitiesIds = $buyed_immunities->where('is_active', 1)->pluck('immunity_id')->toArray();

        $immunitiesWithStatus = $immunities->map(function ($item) use ($purchasedImmunityIds, $activateImmunitiesIds) {
            $item->purchased = in_array($item->id, $purchasedImmunityIds, true);
            $item->is_active = in_array($item->id, $activateImmunitiesIds, true);
            return $item;
        });

        $immunities = $immunitiesWithStatus;




        // dd($immunities->where('is_active', 1));
        foreach ($chances as $chance) {
            // Исходная строка: "['Шериф','Детектив','Журналист','Мститель','Мирный']"
            $jsonString = str_replace("'", '"', $chance->text);
            $rawRoles = json_decode($jsonString, true);
        }


        return view('store', compact('account', 'merch', 'chances', 'club', 'immunities'));
    }
}
