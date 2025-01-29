<?php

namespace App\Exception;

use Exception;
use Illuminate\Support\Facades\Log;

class BaseExternalApiException extends Exception
{

    public const REQUEST_ERROR = 1;
    public const RESPONSE_ERROR = 2;

    public function __construct(string $message = '', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        Log::error("{$this->getExceptionClassName()}: {$message}", [
            'code' => $code,
            'previous' => $previous?->getMessage(),
            'trace' => $this->getTraceAsString(),
        ]);
    }

    public static function requestError(string $message, ?Exception $previous)
    {
        return new static ($message, self::REQUEST_ERROR, $previous);
    }

    public static function responseError(string $message, ?Exception $previous)
    {
        return new static ($message, self::RESPONSE_ERROR, $previous);
    }

    protected function getExceptionClassName(): string
    {
        return get_class($this);
    }
}
