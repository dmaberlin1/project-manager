<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\WeatherException;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class WeatherExceptionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Log::shouldReceive('error')->andReturnNull();
    }

    public function testRequestErrorException(): void
    {
        $exception = WeatherException::requestError();

        $this->assertInstanceOf(WeatherException::class, $exception);
        $this->assertEquals('Ошибка при запросе к WeatherMap API', $exception->getMessage());
    }

    public function testResponseErrorException()
    {
        $exception = WeatherException::responseError();

        $this->assertInstanceOf(WeatherException::class, $exception);
        $this->assertEquals('Некорректный ответ от WeatherMap API', $exception->getMessage());
    }
}
