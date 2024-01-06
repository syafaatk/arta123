<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Ekualisasidetail;
use App\Observers\DetailEkualisasiObserver;

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
        Ekualisasidetail::observe(DetailEkualisasiObserver::class);
    }
}
