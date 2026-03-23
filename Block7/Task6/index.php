<?php

use Task6\Application\App;
use Task6\Infrastructure\Request\Request;
use Task6\Infrastructure\Response\Response;
use Task6\Application\Model\Task;
use Task6\Infrastructure\Logger\Logger;
use Task6\Infrastructure\Middleware\BearerToken;
use PDO;
use PDOException;
use Task6\Infrastructure\Middleware\RequestMiddleware;
use Task6\Infrastructure\Metrics\Metrics;

require_once __DIR__ . "/vendor/autoload.php";

$appInstance = new App(new Request());


$appInstance->addGetRoute('/health', function (Request $request) {
    http_response_code(200);
    echo 'ok';
});

$appInstance->addGetRoute('/ready', function (Request $request) {
    try {
        $db = new PDO("mysql:host=db;dbname=task6", "root", "root");
        $db->query("SELECT 1");
        http_response_code(200);
        echo 'ready';
    } catch (PDOException $exception) {
        http_response_code(503);
        echo 'not ready';
    }
});

$appInstance->addGetRoute('/metrics', function (Request $request) {
    $previousMetricsData = [];
    if (!file_exists("metrics.json")) {
        $previousMetricsData = [
            'request_total' => 0,
            'response_total_ms' => 0,
        ];
        file_put_contents('metrics.json', json_encode($previousMetricsData));
    } else {
        $previousMetricsData = json_decode(file_get_contents("metrics.json"));
    }
    http_response_code(200);
    echo json_encode($previousMetricsData);
});



$appInstance->addPostRoute('/tasks', function (Request $request) {
    $startTime = $_SERVER['REQUEST_START_TIME'] ?? microtime(true);
    Logger::info('Creating an task', ['data' => $request->getBody()]);
    $result = new Task()->add($request->getBody());
    if (!empty($result)) {
        header('Location: /tasks/' . $result['id']);
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(422);
    }
    Metrics::updateMetrics((microtime(true) - $startTime) * 1000, "metrics.json");
}, [new BearerToken(), new RequestMiddleware()]);
$appInstance->addGetRoute('/tasks', function (Request $request) {
    $startTime = $_SERVER['REQUEST_START_TIME'] ?? microtime(true);
    Logger::info('Get all tasks');
    http_response_code(200);
    $limit = $request->getQuery()['limit'] ?? null;
    $cursor = $request->getQuery()['cursor'] ?? null;
    $status = $request->getQuery()['status'] ?? null;

    $result = new Task()->getAll($limit, $cursor);

    if ($status && isset($result['items'])) {
        $sortedResult = [];
        foreach ($result['items'] as $task) {
            if ($task['status'] === $status) {
                $sortedResult[] = $task;
            }
        }
        echo json_encode($sortedResult);
    } else {
        echo json_encode($result);
    }
    Metrics::updateMetrics((microtime(true) - $startTime) * 1000, "metrics.json");
}, [new RequestMiddleware()]);

$appInstance->addGetRoute('/tasks/{id}', function (Request $request, $id) {
    $startTime = $_SERVER['REQUEST_START_TIME'] ?? microtime(true);
    Logger::info('Get an task by id', ['taskId' => $id]);
    $result = new Task()->getById($id);
    if (empty($result)) {
        http_response_code(404);
        echo json_encode([]);
    } else {
        http_response_code(200);
        echo json_encode($result);
    }
    Metrics::updateMetrics((microtime(true) - $startTime) * 1000, "metrics.json");
}, [new RequestMiddleware()]);

$appInstance->addPatchRoute('/tasks/{id}', function (Request $request, $id) {
    $startTime = $_SERVER['REQUEST_START_TIME'] ?? microtime(true);
    Logger::info('Updating an task', ['taskId' => $id]);
    $result = new Task()->editById($id, $request->getBody());
    if (empty($result)) {
        http_response_code(422);
    } else {
        http_response_code(200);
        echo json_encode($result);
    }
    Metrics::updateMetrics((microtime(true) - $startTime) * 1000, "metrics.json");
}, [new BearerToken(), new RequestMiddleware()]);


$appInstance->addDeleteRoute('/tasks/{id}', function (Request $request, $id) {
    $startTime = $_SERVER['REQUEST_START_TIME'] ?? microtime(true);
    Logger::info('Deleting an task', ['taskId' => $id]);
    $result = new Task()->deleteById($id);
    if ($result) {
        http_response_code(204);
    } else {
        http_response_code(404);
    }
    Metrics::updateMetrics((microtime(true) - $startTime) * 1000, "metrics.json");
}, [new BearerToken(), new RequestMiddleware()]);

$appInstance->run();
