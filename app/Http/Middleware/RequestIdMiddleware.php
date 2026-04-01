<?php

namespace Final7\App\Http\Middleware;

use Closure;
use Final7\App\Models\Metrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $id = $request->header('X-Request-Id') ?? (string)str()->uuid();
        $response = $next($request);
        Context::add('requestId', $id);
        Log::info(
            'Incoming request:',
            [
                'path' => $request->path(),
                'method' => $request->method(),
                'X-Request-Id' => $id,
                'timestamp' => new \DateTimeImmutable()->format('c'),
            ]
        );
        $metrics = Metrics::create([
            'path' => $request->path(),
            'method' => $request->method(),
            'x_request_id' => $id,
            'timeDuration' => (int)((microtime(true) - $startTime) * 1000),
        ]);
        
        $response->headers->set('X-Request-Id', $id);
        return $response;
    }
}
