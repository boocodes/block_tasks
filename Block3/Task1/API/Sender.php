<?php


namespace API;

class Sender
{
    public static function SendJsonResponse(array $data, $statusCode): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }
}