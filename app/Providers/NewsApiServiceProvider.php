<?php

namespace App\Providers;

use App\Services\NewsApiService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class NewsApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            'App\Services\NewsApiService',
            function ($app) {
                return new NewsApiService(
                    Config::get('services.news_api.key'),
                    Config::get('services.news_api.url')
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
