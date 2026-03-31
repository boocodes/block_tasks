<?php

$logFile = __DIR__ . '/request.log';

$body = file_get_contents('php://input');
$timestamp = new \DateTimeImmutable()->format('c');

$logEntryLine = "Request. Body: " . $body . '. Timestamp: ' . $timestamp;

file_put_contents($logFile, $logEntryLine, FILE_APPEND);


header('Comtent-Type: application/json');

http_response_code(200);

echo json_encode([
    'status' => 'success',
    'message' => 'Webhook received successfully',
    'timestamp' => $timestamp,
]);
