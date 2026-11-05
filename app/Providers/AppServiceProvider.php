<?php

namespace App\Providers;

use App\Models\Achivment;
use App\Observers\AchivmentObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Achivment::observe(AchivmentObserver::class);
    }
}
