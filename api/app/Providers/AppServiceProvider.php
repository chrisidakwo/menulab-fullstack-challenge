<?php

namespace App\Providers;

use App\Services\WeatherService\Contracts\WeatherService;
use App\Services\WeatherService\NationalWeatherService;
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
        $this->app->bind(WeatherService::class, NationalWeatherService::class);
    }
}
