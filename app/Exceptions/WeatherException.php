<?php

namespace App\Exceptions;

use Exception;


class WeatherException extends BaseExternalApiException
{


    public static function requestError(string $message = 'Ошибка при запросе к WeatherMap API', ?Exception $previous = null): static
    {
        return parent::requestError($message, $previous);
    }

    public static function responseError($message = 'Некорректный ответ от WeatherMap API', ?Exception $previous = null): static
    {
        return parent::responseError($message, $previous);
    }

}
