<?php

$logFile = __DIR__ . '/request.log';

$body = file_get_contents('php://input');
$timestamp = new \DateTimeImmutable()->format('c');

$signature = '';
$secret = 'secret-test';


$headers = null;


if (function_exists('getallheaders')) {
    $headers = getallheaders();
    $signature = $headers['X-Webhook-Signature'];
} else {
    $signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'];
}

$expectedSignature = hash_hmac('sha256', $body, $secret);


$isValidSignature = hash_equals($expectedSignature, $signature);

if (!$isValidSignature) {
    http_response_code(500);
    echo json_encode(['message' => 'Invalid signature. ' . $secret]);
    return;
}


$logEntryLine = "Request. Body: " . $body . '. Timestamp: ' . $timestamp;

file_put_contents($logFile, $logEntryLine, FILE_APPEND);


$body = file_get_contents('php://input');
$queryParams = $_GET;

$tryToFail = isset($queryParams['tryToFail']) && $queryParams['tryToFail'] === 'true';
$die = isset($queryParams['die']) && $queryParams['die'] === 'true';

if ($die) {
    http_response_code(500);
    echo json_encode(['message' => 'Server busy']);
    return;
}
if ($tryToFail) {
    $random = rand(1, 100);
    if ($random <= 50) {
        http_response_code(500);
        echo json_encode(['message' => 'Unluck. Try again']);
        return;
    }
}


header('Comtent-Type: application/json');

http_response_code(200);

echo json_encode([
    'status' => 'success',
    'message' => 'Webhook received successfully',
    'timestamp' => $timestamp,
]);
