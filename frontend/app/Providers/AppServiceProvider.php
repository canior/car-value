<?php

namespace App\Providers;

use App\Service\CarSoldService;
use App\Service\CarSoldServiceInterface;
use App\Service\CarValuePredictionService;
use App\Service\CarValuePredictionServiceInterface;
use App\Service\ImportSoldCarService;
use App\Service\ImportSoldCarServiceInterface;
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

        $this->app->bind(CarSoldServiceInterface::class, function () {
            return new CarSoldService();
        });

        $this->app->bind(ImportSoldCarServiceInterface::class, function () {
            return new ImportSoldCarService();
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
