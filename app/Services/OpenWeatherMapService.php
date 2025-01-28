<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenWeatherMapService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.openweather.url');
        $this->apiKey=config('services.openweather.api_key');
    }

    /**
     * Получение текущей погоды для указанного местоположения.
     *
     * @param string $location
     * @return array|null
     */
    public function getCurrentWeather(string $location): ?array
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $location,
            'appid' => $this->apiKey,
            'units' => 'metric',  // Метрическая - цельсий
        ]);

        if ($response->failed()) {
            throw new \Exception('Ошибка при запросе к OpenWeatherMap API');
        }

        return $response->json();

    }

}
