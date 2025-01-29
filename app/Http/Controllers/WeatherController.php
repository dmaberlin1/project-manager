<?php

namespace App\Http\Controllers;

use App\Exceptions\OpenWeatherException;
use App\Services\WeatherMapInterface;

class WeatherController extends Controller
{
    private WeatherMapInterface $weatherService;

    public function __construct(WeatherMapInterface $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function show(string $location)
    {
        try {
            $weather = $this->weatherService->getCurrentWeather($location);
            return response()->json([
                'location' => $weather['name'],
                'temperature' => $weather['main']['temp'],
                'description' => $weather['weather'][0]['description'],
            ]);
        } catch (OpenWeatherException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
