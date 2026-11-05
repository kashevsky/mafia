<?php

namespace App\Observers;

use App\Http\Services\TelegramService;
use App\Models\Account;
use App\Models\Achivment;

class AchivmentObserver
{
    /**
     * Handle the Achivment "created" event.
     *
     * @param  \App\Models\Achivment  $achivment
     * @return void
     */
    public function created(Achivment $achivment)
    {
        $service = new TelegramService();
        $account = Account::find($achivment->account_id);
        $service->sendAch($achivment, $account->telegram_id);
        // $src = env('APP_URL') . '/medal.png';
        // if (isset($account->telegram_id) && $account->telegram_id != null) {
        //     $service->sendPhoto(
        //         $account->telegram_id,
        //         $src,
        //         $achivment->text
        //     );
        // }
    }

    /**
     * Handle the Achivment "updated" event.
     *
     * @param  \App\Models\Achivment  $achivment
     * @return void
     */
    public function updated(Achivment $achivment)
    {
        //
    }

    /**
     * Handle the Achivment "deleted" event.
     *
     * @param  \App\Models\Achivment  $achivment
     * @return void
     */
    public function deleted(Achivment $achivment)
    {
        //
    }

    /**
     * Handle the Achivment "restored" event.
     *
     * @param  \App\Models\Achivment  $achivment
     * @return void
     */
    public function restored(Achivment $achivment)
    {
        //
    }

    /**
     * Handle the Achivment "force deleted" event.
     *
     * @param  \App\Models\Achivment  $achivment
     * @return void
     */
    public function forceDeleted(Achivment $achivment)
    {
        //
    }
}
