<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class OpenWeatherMapService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openweathermap.org/data/2.5/',
        ]);
    }

    /**
     * Получение текущей погоды для указанного местоположения.
     *
     * @param string $location
     * @return array|null
     */
    public function getCurrentWeather(string $location): ?array
    {
        try {
            $response = $this->client->get('weather', [
                'query' => [
                    'q' => $location,
                    'appid' => env('OPENWEATHERMAP_API_KEY'),
                    'units' => 'metric',
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Ошибка получения данных погоды: ' . $e->getMessage());
            return null;
        }
    }

}
