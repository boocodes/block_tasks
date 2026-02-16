<?php

require_once './vendor/autoload.php';

use API\URLMethodEnum;
use API\Sender;


$inputDataString = file_get_contents('php://input');
$inputDataJson = json_decode($inputDataString, true);


$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? null;
$route = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$accept = $_SERVER['HTTP_ACCEPT'] ?? '';


$working_path = parse_url($route, PHP_URL_PATH);

if ($working_path == '/health' && $method == URLMethodEnum::GET->value) {
    Sender::SendJsonResponse(["status" => "ok"], 200);
} else if ($working_path == '/echo' && $method == URLMethodEnum::POST->value) {
    if (!json_validate($inputDataString)) {
        Sender::SendJsonResponse([
            'status' => 'false',
            'message' => json_last_error_msg()
        ], 400);
    } else {
        Sender::SendJsonResponse($inputDataJson, 200);
    }
} else if ($working_path == '/headers' && $method == URLMethodEnum::GET->value) {
    Sender::SendJsonResponse([
        'User-Agent' => $userAgent,
        'Accept' => $accept,
        'Authorization' => $authorization,
    ], 200);
} else {
    Sender::SendJsonResponse([
        "status" => "error",
        "message" => "Page not found"
    ], 404);
}

/* 1. curl.exe -X GET http://localhost:8000/headers -H "Authorization: Basic dXNlcm5hbWU6cGFzc3dvcmQ=" -H "Accept: application/json"
    {"User-Agent":"curl\/8.16.0","Accept":"application\/json","Authorization":"Basic dXNlcm5hbWU6cGFzc3dvcmQ="}
*/
/*
    2.curl.exe -X POST http://localhost:8000/echo -H "Content-Type: application/json" -d '{\"name\":\"Nick\",\"surname\":Felker}'
 */