<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OpenWeatherMapService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.openweather.url');
        $this->apiKey = config('services.openweather.api_key');
    }

    /**
     * Получение текущей погоды для указанного местоположения.
     *
     * @param string $location
     * @return array|null
     */
    public function getCurrentWeather(string $location): ?array
    {
        $cacheKey = "weather_{$location}";
        //Кеш погоды на 1ч
        return Cache::remember($cacheKey, 3600, function () use ($location) {

            $response = Http::get($this->apiUrl, [
                'q' => $location,
                'appid' => $this->apiKey,
                'units' => 'metric',  // Метрическая - цельсий
            ]);

            if ($response->failed()) {
                throw new \Exception('Ошибка при запросе к OpenWeatherMap API');
            }
            return $response->json();
        });
    }

}
