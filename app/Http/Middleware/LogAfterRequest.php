<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;

class LogAfterRequest
{

    public function handle($request, \Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        Log::info('Request', [
                'URL' => $request->url(),
                'Method' => $request->method(),
                'Params' => (!empty($request->all()) ? json_encode($request->except(['password'])) : ''),
                'Response' => $response
            ]);
    }
}
