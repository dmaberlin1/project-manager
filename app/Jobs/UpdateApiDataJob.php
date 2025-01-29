<?php

namespace App\Jobs;

use App\Services\OpenWeatherMapService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateApiDataJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $location)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(OpenWeatherMapService $weatherMapService): void
    {
        $weatherMapService->getCurrentWeather($this->location);
    }
}
