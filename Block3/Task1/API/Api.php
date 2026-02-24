<?php

namespace API;

use API\Sender;


Routes::get('/health', function (Request $request) {
    Sender::SendJsonResponse(['status' => 'ok'], 200);
});
Routes::get('/headers', function (Request $request) {
    Sender::SendJsonResponse([
        'User-Agent' => $request->getHeaders()['HTTP_USER_AGENT'] ?? '',
        'Accept' => $request->getHeaders()['HTTP_ACCEPT'] ?? '',
        'Authorization' => $request->getHeaders()['HTTP_AUTHORIZATION'] ?? '',
    ], 200);
});
Routes::post('/echo', function (Request $request) {
    if(!json_validate($request->getInputData()))
    {
        Sender::SendJsonResponse([
            'status' => 'error',
            'message' => json_last_error_msg(),
        ], 400);
    }
    else
    {
        Sender::SendJsonResponse(json_decode($request->getInputData(), true), 200);
    }
});
Routes::pageNotFound(function (Request $request) {
    Sender::SendJsonResponse([
        'status' => 'error',
        'message' => 'Page not found',
    ], 404);
});
