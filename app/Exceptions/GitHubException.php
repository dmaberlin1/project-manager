<?php

namespace App\Exceptions;

use Exception;

class GitHubException extends BaseExternalApiException
{

    public static function requestError(string $message = 'Ошибка при запросе к GitHub API', ?Exception $previous = null): static
    {
        return parent::requestError($message, $previous);
    }

    public static function responseError(string $message = 'Некорректный ответ от GitHub API', ?Exception $previous = null): static
    {
        return parent::responseError($message, $previous);
    }


}
