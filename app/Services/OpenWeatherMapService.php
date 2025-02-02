<?php

namespace App\Services;

use App\Exceptions\WeatherException;
use App\Services\Interfaces\WeatherMapInterface;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OpenWeatherMapService implements WeatherMapInterface
{
    private string $apiUrl;
    private string $apiKey;

    public function __construct(string $apiKey, string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function getCurrentWeather(string $location): ?array
    {
        $cacheKey = "weather_{$location}";
        return Cache::remember($cacheKey, 3600, function () use ($location) {
            try {
                $response = Http::get($this->apiUrl, [
                    'q' => $location,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                ]);


                if ($response->failed()) {

                    $statusCode = $response->status();
                    $errorMessage = $response->json('message', 'Неизвестная ошибка');
                    throw WeatherException::requestError($statusCode, $errorMessage);
                }

                $data = $response->json();

                if (!isset($data['name'], $data['main']['temp'], $data['weather'][0]['description'])) {
                    throw WeatherException::responseError();
                }

                return $data;
            } catch (RequestException $e) {
                throw new WeatherException('Ошибка при запросе данных о погоде: ' . $e->getMessage(), 0, $e);
            } catch (\Exception $e) {
                throw new WeatherException('Ошибка при получении данных о погоде: ' . $e->getMessage(), 0, $e);
            }
        });
    }
}
