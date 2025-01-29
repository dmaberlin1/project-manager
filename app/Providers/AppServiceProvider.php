<?php

namespace App\Providers;

use App\Services\GitHubService;
use App\Services\OpenWeatherMapService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{


    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        }

        $this->app->singleton(GitHubService::class, function ($app) {
            return new GitHubService(
                config('services.github.url'),
                config('services.github.token')
            );
        });

        $this->app->singleton(OpenWeatherMapService::class, function ($app) {
            return new OpenWeatherMapService(
                config('services.openweather.url'),
                config('services.openweather.api_key')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
