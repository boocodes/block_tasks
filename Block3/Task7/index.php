<?php

use Task7\Application\App;
use Task7\Infrastructure\Request\Request;
use Task7\Infrastructure\Response\Response;
use Task7\Application\Model\Task;
use Task7\Infrastructure\Middleware\BearerToken;
use Task7\Infrastructure\WebHook\WebHookWorker;

require_once __DIR__ . "/vendor/autoload.php";

$appInstance = new App(new Request());

$appInstance->addPostRoute('/tasks', function (Request $request) {
    $result = new Task()->add($request->getBody());
    if (!empty($result)) {
        header('Location: /tasks/' . $result['id']);
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(422);
    }
}, [new BearerToken()]);
$appInstance->addGetRoute('/tasks', function (Request $request) {
    $result = new Task()->getAll($request->getQuery()['limit'], $request->getQuery()['cursor']);
    if (isset($request->getQuery()['status'])) {
        $sortedResult = [];
        foreach ($result as $task) {
            if ($task['status'] === $request->getQuery()['status']) {
                $sortedResult = $task;
            }
        }
        var_dump($sortedResult);
    } else {
        var_dump($result);
    }
    http_response_code(200);
});

$appInstance->addGetRoute('/tasks/{id}', function (Request $request, $id) {
    $result = new Task()->getById($id);
    if(empty($result))
    {
        http_response_code(404);
        echo json_encode([]);
    }
    else
    {
        http_response_code(200);
        echo json_encode($result);
    }
});

$appInstance->addPatchRoute('/tasks/{id}', function (Request $request, $id) {
    $result = new Task()->editById($id, $request->getBody());
    if(empty($result))
    {
        http_response_code(422);
    }
    else
    {
        http_response_code(200);
        echo json_encode($result);
    }
}, [new BearerToken()]);


$appInstance->addDeleteRoute('/tasks/{id}', function (Request $request, $id) {
    $result = new Task()->deleteById($id);
    if($result)
    {
        http_response_code(204);
    }
    else
    {
        http_response_code(404);
    }
}, [new BearerToken()]);


$appInstance->addPostRoute('/webhook-receiver', function (Request $request) {
    $payload = $request->getBody();
    $logFile = __DIR__ . '/var/logs/webhook.log';
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'payload' => $payload,
        'headers' => $request->getHeaders(),
    ];
    file_put_contents($logFile, json_encode($logEntry, JSON_PRETTY_PRINT). PHP_EOL, FILE_APPEND);
    http_response_code(201);
    echo json_encode(['status' => 'received']);
});

$appInstance->addGetRoute('/exec-webhook', function (Request $request) {
    $worker = new WebHookWorker();
    $worker->proccesQueue();
    echo json_encode(['status' => 'processed']);
});

$appInstance->run();
