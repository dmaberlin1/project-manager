<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception)
    {
        // Логируем только один раз, используя метод buildLogContext
        if ($this->shouldReport($exception)) {
            Log::error('Произошла ошибка:', $this->buildLogContext($exception));
        }

        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        $statusCode = $exception instanceof HttpException
            ? $exception->getStatusCode()
            : 500;

        $response = [
            'error' => [
                'message' => $this->getErrorMessage($exception, $statusCode),
                'status_code' => $statusCode,
            ],
        ];

        if (config('app.debug') && $statusCode === 500) {
            $response['error']['trace'] = $exception->getTrace();
        }

        return response()->json($response, $statusCode);
    }

    protected function getErrorMessage(Throwable $exception, int $statusCode): string
    {
        if ($exception instanceof HttpException) {
            return $exception->getMessage() ?: 'Произошла ошибка на стороне сервера.';
        }

        return match ($statusCode) {
            404 => 'Ресурс не найден.',
            403 => 'Доступ запрещён.',
            401 => 'Не авторизован.',
            400 => 'Неверный запрос.',
            default => 'Произошла непредвиденная ошибка. Пожалуйста, попробуйте позже.',
        };
    }

    protected function buildLogContext(Throwable $exception): array
    {
        return [
            'message' => $exception->getMessage(),
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'user_id' => optional(auth()->user())->id,
        ];
    }
}
