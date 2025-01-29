<?php

namespace App\Services;

use App\Exception\OpenWeatherException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OpenWeatherMapService
{
    private string $apiUrl;
    private string $apiKey;

    public function __construct(string $apiKey, string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
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
            try {
                $response = Http::get($this->apiUrl, [
                    'q' => $location,
                    'appid' => $this->apiKey,
                    'units' => 'metric',  // Метрическая - цельсий
                ]);

                if ($response->failed()) {
                    throw OpenWeatherException::requestError();
                }

                $data = $response->json();
                if (!isset($data['name'], $data['main']['temp'], $data['weather'][0]['description'])) {
                    throw OpenWeatherException::responseError();
                }
                return $data;
            } catch (\Exception $e) {
                throw new OpenWeatherException('Ошибка при получении данных о погоде', 0, $e);
            }
        });
    }

}
