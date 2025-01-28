<?php

namespace App\Console\Commands;

use App\Services\GitHubService;
use App\Services\OpenWeatherMapService;
use Illuminate\Console\Command;

class UpdateWeatherAndGitHubData extends Command
{
    protected $signature = 'update:data';
    protected $description = 'Обновление данных из OpenWeatherMap и GitHub API';

    protected $weatherService;
    protected $githubService;

    public function __construct(OpenWeatherMapService $weatherService, GitHubService $gitHubService)
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
