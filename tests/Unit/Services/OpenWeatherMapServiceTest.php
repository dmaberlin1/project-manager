<?php

namespace Tests\Unit\Services;

use App\Exceptions\OpenWeatherException;
use App\Services\OpenWeatherMapService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenWeatherMapServiceTest extends TestCase
{
    private OpenWeatherMapService $weatherService;

    public function setUp(): void
    {
        parent::setUp();

        $this->weatherService = new OpenWeatherMapService('fake_api_key', 'http://fakeapi.com');
    }

    public function test_get_current_weather_success()
    {
        Http::fake([
            'http://fakeapi.com' => Http::response([
                'name' => 'London',
                'main' => ['temp' => 15],
                'weather' => [['description' => 'Clear sky']],
            ], 200),
        ]);

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn([
                'name' => 'London',
                'main' => ['temp' => 15],
                'weather' => [['description' => 'Clear sky']],
            ]);

        $weather = $this->weatherService->getCurrentWeather('London');

        $this->assertIsArray($weather);
        $this->assertEquals('London', $weather['name']);
        $this->assertEquals(15, $weather['main']['temp']);
        $this->assertEquals('Clear sky', $weather['weather'][0]['description']);
    }

    public function test_get_current_weather_failed_response()
    {
        Http::fake([
            'http://fakeapi.com' => Http::response([], 500),
        ]);

        $this->expectException(OpenWeatherException::class);

        $this->weatherService->getCurrentWeather('London');
    }

    public function test_get_current_weather_missing_data()
    {
        Http::fake([
            'http://fakeapi.com' => Http::response([
                'name' => 'London',
            ], 200),
        ]);

        $this->expectException(OpenWeatherException::class);

        $this->weatherService->getCurrentWeather('London');
    }
}
