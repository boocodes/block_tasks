<?php

namespace Task7\Infrastructure\Middleware;

use Task7\Domain\Interfaces\Middleware;
use Task7\Infrastructure\Request\Request;


class BearerToken implements Middleware
{
    public function run(Request $request): bool
    {

        $headerAuth = $request->getHeaders()['HTTP_AUTHORIZATION'] ?? $request->getHeaders()['Authorization'] ?? null;
        if($headerAuth === null || !isset($request->getConfig()['API_KEY'])) {
            header('WWW-Authenticate: Bearer');
            header('HTTP/1.0 401 Unauthorized');
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Authentication required']);
            return false;
        }

        $headerToken = $request->getHeaders()['HTTP_AUTHORIZATION'] ?? $request->getHeaders()['Authorization'];
        $bearerTokenInput = '';
        $matches = [];
        if(preg_match('/Bearer\s(\S+)/', $headerToken, $matches))
        {
            $bearerTokenInput = $matches[1];
        }
        else{
            return false;
        }
        if($bearerTokenInput !== $request->getConfig()['API_KEY']) {
            header('WWW-Authenticate: Bearer');
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Authorization wrong. Current - ' . $bearerTokenInput . '. Input - ' . $request->getConfig()['API_KEY']]);
            return false;
        }
        return true;
    }
}