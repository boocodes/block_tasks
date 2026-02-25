<?php

use Task5\Application\App;
use Task5\Domain\Enums\HttpMethods;
use Task5\Infrastructure\Request\Request;
use Task5\Infrastructure\Middleware\BearerToken;
use Task5\Domain\Enums\TaskStatus;

require_once __DIR__ . "/vendor/autoload.php";

$appInstance = new App(new Request());


//curl.exe -X GET -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c" -H "Content-Type: application/json" http://127.0.0.1:8000/tasks/10
$appInstance->addGetRoute('/tasks/{id}', function (Request $request, $id) {
    $result = new \Task5\Application\Model\Task()->getById($id);
    if(empty($result))
    {
        http_response_code(404);
    }
    else
    {
        http_response_code(200);
        echo json_encode($result);
    }

});

//curl.exe -X GET -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c" -H "Content-Type: application/json" http://127.0.0.1:8000/tasks?limit=2
$appInstance->addGetRoute('/tasks', function (Request $request) {

    $result = new \Task5\Application\Model\Task()->getAll();

    $status = $request->getQuery()['status'] ?? null;

    $status = TaskStatus::tryFrom($status) ?? null;


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
    http_response_code(200);
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
    $inputData = $request->getBody();
    if(isset($inputData['status']))
    {
        $inputData['status'] = TaskStatus::tryFrom($inputData['status']) ?? TaskStatus::NEW;
    }
    $result = new \Task5\Application\Model\Task()->add($inputData);

    if(empty($result))
    {
        http_response_code(409);
    }
    else
    {
        http_response_code(201);
        header('Location: /tasks/' . $result['id']);
        echo json_encode($result);
    }
}, [new BearerToken()]);
//curl.exe -X PATCH -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c" -H "Content-Type: application/json" -d @"
//>> {
//>>     \"title\": \"text\",
//>>     \"description\": \"EDITEDtest\"
//>> }
//>> "@ http://127.0.0.1:8000/tasks/6998726dbbe73
$appInstance->addPatchRoute('/tasks/{id}', function (Request $request, $id) {
    $result = new \Task5\Application\Model\Task()->editById($id, $request->getBody());
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
//curl.exe -X DELETE -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c" -H "Content-Type: application/json" http://127.0.0.1:8000/tasks/10
$appInstance->addDeleteRoute('/tasks/{id}', function (Request $request, $id) {
    $result = new \Task5\Application\Model\Task()->deleteById($id);
    if($result)
    {
        http_response_code(204);
    }
    else
    {
        http_response_code(404);
    }
}, [new BearerToken()]);



$appInstance->run();