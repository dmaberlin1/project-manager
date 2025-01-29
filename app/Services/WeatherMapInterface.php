<?php

namespace App\Services;

interface WeatherMapInterface
{
    public function getCurrentWeather(string $location): ?array;
}
