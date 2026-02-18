<?php

namespace Task5;

use Task5\Core\App;



class Auth
{
    public function __construct()
    {
    }
    public function bearerTokenVerify(): bool
    {
        if (!isset(App::getHeaders()['HTTP_AUTHORIZATION']) && !isset(App::getHeaders()['Authorization'])) {
            header('WWW-Authenticate: Bearer');
            Sender::SendJsonResponse([
                ['status' => 'error',
                    'message' => 'Authorization header required']
            ], 401);
            return false;
        }
        else
        {
            $headerAuth = App::getHeaders()['HTTP_AUTHORIZATION'] ?? App::getHeaders()['Authorization'];
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