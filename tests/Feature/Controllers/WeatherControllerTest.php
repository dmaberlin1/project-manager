<?php

namespace Tests\Feature\Controllers;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherControllerTest extends TestCase
{
    public function test_show_weather_success()
    {
        Http::fake([
            'http://fakeapi.com' => Http::response([
                'name' => 'London',
                'main' => ['temp' => 15],
                'weather' => [['description' => 'Clear sky']],
            ], 200),
        ]);

        $response = $this->getJson('/weather/London');

        $response->assertStatus(200)
            ->assertJson([
                'location' => 'London',
                'temperature' => 15,
                'description' => 'Clear sky',
            ]);
    }

    public function test_show_weather_error()
    {
        Http::fake([
            'http://fakeapi.com' => Http::response([], 500),
        ]);

        $response = $this->getJson('/weather/London');

        $response->assertStatus(500)
            ->assertJson([
                'error' => 'Ошибка при получении данных о погоде',
            ]);
    }
}
