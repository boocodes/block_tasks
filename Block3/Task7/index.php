<?php

use Task7\Application\App;
use Task7\Domain\Enums\HttpMethods;
use Task7\Infrastructure\Request\Request;
use Task7\Infrastructure\Middleware\BearerToken;
use Task7\Application\Model\Task;
use Task7\Infrastructure\WebHook\WebHookWorker;

require_once __DIR__ . "/vendor/autoload.php";

$appInstance = new App(new Request());


$appInstance->addPostRoute('/webhook-receiver', function (Request $request) {
    $payload = $request->getBody();

    $logFile = __DIR__ . '/var/logs/webhook.log';
    $logEntry = [
        'timestamp' => data('Y-m-d H:i:s'),
        'payload' => $payload,
        'headers' => $request->getHeaders(),
    ];
    file_put_contents($logFile, json_encode($logEntry, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
    http_response_code(404);
    echo json_encode(['status' => 'received']);
});


$appInstance->addGetRoute('/exec-webhook', function (Request $request) {
    $worker = new WebhookWorker();
    $worker->proccesQueue();
    echo json_encode(['status' => 'queue processed']);
});

//curl.exe -X GET -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c" -H "Content-Type: application/json" http://127.0.0.1:8000/tasks/10
$appInstance->addGetRoute('/tasks/{id}', function (Request $request, $id) {
    $result = new Task()->getById($id);
    var_dump($result);
}, [new BearerToken()]);

//curl.exe -X GET -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c" -H "Content-Type: application/json" http://127.0.0.1:8000/tasks?limit=2
$appInstance->addGetRoute('/tasks', function (Request $request) {

    $result = new Task()->getAll();

    $status = $request->getQuery()['status'] ?? null;
    $cursor = $request->getQuery()['cursor'] ?? null;
    $limit = isset($request->getQuery()['limit']) ? (int)$request->getQuery()['limit'] : null;

    if ($limit < 1) $limit = 1;
    if ($limit > 100) $limit = 100;

    if ($status !== null) {
        var_dump("ok");
        $filteredResult = [];
        foreach ($result as $task) {
            if (isset($task['status']) && $task['status'] === $status) {
                $filteredResult[] = $task;
            }
        }
        $result = $filteredResult;
    }

    $itemsList = [];
    $startIndex = 0;
    if ($cursor !== null) {
        foreach ($result as $taskKey => $taskValue) {
            if ($taskValue['id'] === $cursor) {
                $startIndex = $cursor++;
            }
        }
    }
    $result = array_slice($result, $startIndex, $limit);

    $nextCursor = null;
    if (count($result) === $limit && count($result) > $startIndex + $limit) {
        $nextCursor = end($result)['id'];
    }
    $response = [
        'items' => $result,
        'nextCursor' => $nextCursor,
    ];
    echo json_encode($response);
});
//curl.exe -X POST -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c" -H "Content-Type: application/json" -d @"
//>> {
//>>     \"title\": \"text\",
//>>     \"description\": \"test\"
//>> }
//>> "@ http://127.0.0.1:8000/tasks
$appInstance->addPostRoute('/tasks', function (Request $request) {
    $result = new Task()->add($request->getBody(), '');
});
//curl.exe -X PATCH -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c" -H "Content-Type: application/json" -d @"
//>> {
//>>     \"title\": \"text\",
//>>     \"description\": \"EDITEDtest\"
//>> }
//>> "@ http://127.0.0.1:8000/tasks/6998726dbbe73
$appInstance->addPatchRoute('/tasks/{id}', function (Request $request, $id) {
    $result = new Task()->editById($id, $request->getBody());
});
//curl.exe -X DELETE -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c" -H "Content-Type: application/json" http://127.0.0.1:8000/tasks/10
$appInstance->addDeleteRoute('/tasks/{id}', function (Request $request, $id) {
    $result = new Task()->deleteById($id);
});
$appInstance->run();