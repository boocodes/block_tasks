<?php

namespace Task2\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Context;

class RequestIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->header('X-Request-Id') ?? (string)str()->uuid();
        Context::add('requestId', $id);
        $response = $next($request);
        $response->headers->set('X-Request-Id', $id);
        return $response;
    }
}
