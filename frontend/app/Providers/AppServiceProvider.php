<?php

namespace App\Providers;

use App\Service\CarValuePredictionService;
use App\Service\CarValuePredictionServiceInterface;
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
        $this->app->bind(CarValuePredictionServiceInterface::class, function () {
            return new CarValuePredictionService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
