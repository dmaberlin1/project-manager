<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;


class LogHttpRequests
{

    public function handle($request, Closure $next)
    {
        Log::channel('http')->info('HTTP Request', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'body' => $request->all(),
        ]);

        return $next($request);
    }
}
