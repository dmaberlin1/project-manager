<?php

namespace App\Console\Commands;

use App\Services\Interfaces\GitHubInterface;
use App\Services\Interfaces\WeatherMapInterface;
use Illuminate\Console\Command;

class UpdateWeatherAndGitHubData extends Command
{
    protected $signature = 'update:data';
    protected $description = 'Обновление данных из OpenWeatherMap и GitHub API';

    protected WeatherMapInterface $weatherService;
    protected GitHubInterface $githubService;

    public function __construct(WeatherMapInterface $weatherService, GitHubInterface $gitHubService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
        $this->githubService = $gitHubService;
    }

    public function handle()
    {
        try {
            $weather = $this->weatherService->getCurrentWeather('London');
            $this->info("Погода в Лондоне: {$weather['main']['temp']} °C");

            $repos = $this->githubService->getUserRepositories('dmaberlin1');
            $this->info("Репозитории пользователя dmaberlin1: " . count($repos));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
