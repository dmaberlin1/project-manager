<?php

namespace App\Console\Commands;

use App\Services\OpenWeatherMapService;
use App\Services\GitHubService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateApiData extends Command
{
    protected $signature='api:update';
    protected $description='Обновление данных из внешних API';

    protected $weatherService;
    protected $githubService;


    public function __construct(OpenWeatherMapService $weatherService,GitHubService $gitHubService)
    {
        parent::__construct();
        $this->weatherService=$weatherService;
        $this->githubService=$gitHubService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Получение данных о погоде
        $weatherData=$this->weatherService->getCurrentWeather('Kyiv');
        if($weatherData){
            Log::info('Погода обновлена: ',$weatherData);
        }

        // Получение списка репозиториев
        $repos=$this->githubService->getUserRepositories('dmaberlin1');
        if($repos){
            Log::info('Репозитории обновлены',$repos);
        }

        $this->info('Данные успешно обновлены');
    }
}
