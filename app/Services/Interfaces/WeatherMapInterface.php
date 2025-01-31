<?php

namespace App\Services\Interfaces;

interface WeatherMapInterface
{
    public function getCurrentWeather(string $location): ?array;
}
