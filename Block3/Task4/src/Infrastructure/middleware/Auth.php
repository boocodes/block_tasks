<?php

namespace Task4\Infrastructure\middleware;
use Task4\Core\Sender;
use Task4\Core\App;

class Auth
{
    public static function auth(): bool
    {
        if (!isset($_SERVER['HTTP_AUTHORIZATION']) && !isset($_SERVER['Authorization'])) {
            header('WWW-Authenticate: Bearer');
            Sender::SendJsonResponse([
                ['status' => 'error',
                    'message' => 'Authorization header required']
            ], 401);
            return false;
        }
        else
        {
            $headerAuth = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['Authorization'];
            $bearerToken = "";
            if(preg_match('/Bearer\s(\S+)/', $headerAuth, $matches))
            {
                $bearerToken = $matches[1];
            }
            if($bearerToken !== App::getBearerToken())
            {
                Sender::SendJsonResponse([
                    ['status' => 'error',
                        'message' => 'Authorization wrong']
                ], 403);
                return false;
            }
            else
            {
                return true;
            }
        }
    }
}