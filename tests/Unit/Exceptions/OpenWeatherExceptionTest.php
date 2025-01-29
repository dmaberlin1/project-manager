<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\OpenWeatherException;
use PHPUnit\Framework\TestCase;

class OpenWeatherExceptionTest extends TestCase
{
    public function testRequestErrorException()
    {
        $exception = OpenWeatherException::requestError();

        $this->assertInstanceOf(OpenWeatherException::class, $exception);
        $errorRequest = 'Ошибка при запросе к OpenWeatherMap API';
        $this->assertEquals($errorRequest, $exception->getMessage());
    }

    public function testResponseErrorException()
    {
        $exception = OpenWeatherException::responseError();

        $this->assertInstanceOf(OpenWeatherException::class, $exception);
        $errorResponse = 'Некорректный ответ от OpenWeatherMap API';
        $this->assertEquals($errorResponse, $exception->getMessage());
    }
}
