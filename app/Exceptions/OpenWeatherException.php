<?php

namespace App\Exceptions;

use Exception;


class OpenWeatherException extends BaseExternalApiException
{


    public static function requestError(string $message = 'Ошибка при запросе к OpenWeatherMap API', ?Exception $previous = null): static
    {
        return parent::requestError($message, $previous);
    }

    public static function responseError($message = 'Некорректный ответ от OpenWeatherMap API', ?Exception $previous = null): static
    {
        return parent::responseError($message, $previous);
    }

}
