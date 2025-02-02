<?php

namespace App\Providers;

use App\Services\GitHubService;
use App\Services\Interfaces\AuthInterface;
use App\Services\Interfaces\GitHubInterface;
use App\Services\Interfaces\MailInterface;
use App\Services\Interfaces\StatisticsInterface;
use App\Services\Interfaces\WeatherMapInterface;
use App\Services\MailService;
use App\Services\OpenWeatherMapService;
use App\Services\StatisticsService;
use Illuminate\Support\Facades\Config;
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


        $this->app->bind(MailInterface::class, MailService::class);
        $this->app->bind(WeatherMapInterface::class, OpenWeatherMapService::class);
        $this->app->bind(AuthInterface::class, AuthInterface::class);
        $this->app->bind(StatisticsInterface::class, StatisticsService::class);
        $this->app->bind(GitHubInterface::class, GitHubService::class);

        $this->app->singleton(GitHubService::class, function () {
            return new GitHubService(
                Config::get('services.github.url'),
                Config::get('services.github.token')
            );
        });

        $this->app->singleton(OpenWeatherMapService::class, function () {
            return new OpenWeatherMapService(
                Config::get('services.openweather.url'),
                Config::get('services.openweather.api_key')
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
