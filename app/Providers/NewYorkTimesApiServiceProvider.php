<?php

namespace App\Providers;

use App\Services\NewYorkTimesApiService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class NewYorkTimesApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            'App\Services\NewYorkTimesApiService',
            function ($app) {
                return new NewYorkTimesApiService(
                    Config::get('services.new_york_times_api.key'),
                    Config::get('services.new_york_times_api.url')
                );
            }
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
