<?php

use Task5\Application\App;
use Task5\Domain\Enums\HttpMethods;
use Task5\Infrastructure\Request\Request;
use Task5\Infrastructure\Middleware\BearerToken;

require_once __DIR__ . "/vendor/autoload.php";

$appInstance = new App(new Request());

$appInstance->addGetRoute('/tasks/{id}', function (Request $request, $id) {
    $result = new \Task5\Application\Model\Task()->getById($id);
    var_dump($result);
}, [new BearerToken()]);


$appInstance->addGetRoute('/tasks', function (Request $request) {

    $result = new \Task5\Application\Model\Task()->getAll();

    $status = $request->getQuery()['status'] ?? null;
    $cursor = $request->getQuery()['cursor'] ?? null;
    $limit = isset($request->getQuery()['limit']) ? (int)$request->getQuery()['limit'] : null;

    if($limit < 1 ) $limit = 1;
    if($limit > 100) $limit = 100;

    if($status !== null)
    {
        var_dump("ok");
       $filteredResult = [];
       foreach ($result as $task)
       {
           if(isset($task['status']) && $task['status'] === $status)
           {
               $filteredResult[] = $task;
           }
       }
       $result = $filteredResult;
    }

    $itemsList = [];
    $startIndex = 0;
    if($cursor !== null)
    {
        foreach ($result as $taskKey => $taskValue)
        {
            if($taskValue['id'] === $cursor)
            {
                $startIndex = $cursor++;
            }
        }
    }
    $result = array_slice($result, $startIndex, $limit);

    $nextCursor = null;
    if(count($result) === $limit && count($result) > $startIndex + $limit)
    {
        $nextCursor = end($result)['id'];
    }
    $response = [
        'items' => $result,
        'nextCursor' => $nextCursor,
    ];
    echo json_encode($response);
});

$appInstance->addPostRoute('/tasks', function (Request $request) {
    $result = new \Task5\Application\Model\Task()->add($request->getBody(), '');
});
$appInstance->addPatchRoute('/tasks/{id}', function (Request $request, $id) {
    $result = new \Task5\Application\Model\Task()->editById($id, $request->getBody());
});
$appInstance->addDeleteRoute('/tasks/{id}', function (Request $request, $id) {
    $result = new \Task5\Application\Model\Task()->deleteById($id);
});
$appInstance->run();