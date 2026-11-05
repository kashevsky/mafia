<?php

namespace App\Http\Helpers;

use App\Models\Account;
use App\Models\AccountChance;
use App\Models\AccountImmunity;
use App\Models\AccountMerch;
use App\Models\AccountProbability;
use App\Models\Transaction;
use Carbon\Carbon;



class TransactionHelper
{
    public static function addCoins($account_id, $amount) {
        $account = Account::find($account_id);
        $account->balance = $account->balance + $amount;
        Transaction::create([
            'account_id' => $account_id,
            'amount' => '+'.$amount,
            'type' => 'deposit',
            'date' => Carbon::now()->format('Y-m-d'),
            'comment' => 'Пополнение баланса'
        ]);
        $account->save();
    }

    public static function makePay($item, $type, $account)
    {
        $account->balance = $account->balance - $item->price;
        $account->save();


        if($type == 'merch') {
            AccountMerch::create([
                'account_id' => $account->id,
                'merch_id' => $item->id
            ]);
        } elseif($type == 'probabilities') {
            AccountProbability::create([
                'account_id' => $account->id,
                'probability_id' => $item->id
            ]);
        } elseif($type == 'immunities') {
            AccountImmunity::create([
                'account_id' => $account->id,
                'immunity_id' => $item->id
            ]);
        }

        Transaction::create([
            'account_id' => $account->id,
            'amount' => '-'.$item->price,
            'type' => 'pay',
            'date' => Carbon::now()->format('Y-m-d'),
            'comment' => 'Покупка: '.$item->title
        ]);
    }
}
