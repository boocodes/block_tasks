<?php 

namespace Task7\Infrastructure\Middleware;

use Task7\Domain\Interfaces\Middleware;
use Task7\Infrastructure\Request\Request;
use Task7\Infrastructure\Logger\Logger;

class RequestMiddleware implements Middleware
{
    public function run(Request $request): bool
    {
        $headers = $request->getHeaders();
        $requestId = $headers['HTTP_X_REQUEST_ID'] ?? $headers['X-Request-Id'] ?? null;

        if(!$requestId)
            {
                $requestId = uniqid();
            }
        $_SERVER['REQUEST_ID'] = $requestId;
        $_SERVER['REQUEST_START_TIME'] = microtime(true);
        Logger::info('Request started', [
            'method' => $request->getMethod()->value,
            'endpoint' => $request->getEndpoint(),
        ]);
        return true;
    }
}