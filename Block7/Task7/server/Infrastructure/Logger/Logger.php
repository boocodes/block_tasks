<?php

namespace Task7\Infrastructure\Logger;

use DateTimeImmutable;

class Logger
{
    public static function info($message, $context = [])
    {
        $requestId = $_SERVER['REQUEST_ID'] ?? 'no_request_id';

        $log = [
            'created_at' => new DateTimeImmutable()->format('c'),
            'request_id' => $requestId,
            'message' => $message,
            'context' => $context,
        ];
        file_put_contents(
            __DIR__ . '/../../app.log',
            json_encode($log) . PHP_EOL,
            FILE_APPEND
        );
        return $log;
    }
}