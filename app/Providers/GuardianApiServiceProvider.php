<?php

namespace App\Providers;

use App\Services\GuardianApiService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class GuardianApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            'App\Services\GuardianApiService',
            function ($app) {
                return new GuardianApiService(
                    Config::get('services.guardian_api.key'),
                    Config::get('services.guardian_api.url')
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
