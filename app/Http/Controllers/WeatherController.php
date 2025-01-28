<?php

namespace App\Http\Controllers;

use App\Services\OpenWeatherMapService;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(OpenWeatherMapService $weatherService)
    {
        $this->weatherService=$weatherService;
    }

    public function show(string $location)
    {
    try{
        $weather=$this->weatherService->getCurrentWeather($location);
        return response()->json([
            'location'=>$weather['name'],
            'temperature'=>$weather['main']['temp'],
            'description'=>$weather['weather'][0]['description'],
        ]);
    }catch (\Exception $e){
        return response()->json(['error'=>$e->getMessage()],500);
    }
    }
}
