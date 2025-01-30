<?php

namespace App\Http\Controllers\External;

use App\Exceptions\WeatherException;
use App\Http\Controllers\Controller;
use App\Services\Interfaces\WeatherMapInterface;

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
        } catch (WeatherException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'code' => $e->getCode() ?: 400,
                'details' => $e->getTraceAsString(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }
}
