<?php

namespace Task2\Core;


use DateTime;
use Task2\Core\Request;
use Task2\Core\Routes;
use Task2\Core\Sender;
use Task2\Infrastructure\TaskRepository;
use Task2\Application\DTO\Task;
use Task2\Domain\Enums\StatusEnum;

Routes::post('/tasks', function (Request $request) {
    $inputJson = json_decode($request->getInputData(), true);
    if($inputJson['title'] !== '')
    {
        $task = new Task(
            $inputJson['title'],
            $inputJson['description'] ?? "",
            StatusEnum::New,
        );
        $taskRepository = new TaskRepository();
        $taskRepository->addTask($task);

        Sender::SendJsonResponse(['id'=>$task->id,
            'title'=>$task->title,
            'description'=>$task->description,
            'status'=>$task->status,
            'createdAt'=>$task->createdAt], 200);
    }
    else
    {
        Sender::SendJsonResponse(['status' => 'error', 'message' => 'Can not create an task. Title is required.'], 400);
    }

});



Routes::get('/tasks/{$id}', function (Request $request) {
   $res = [
       'res' => 'ok!'
   ];
   Sender::SendJsonResponse(($res), 200);
});


Routes::pageNotFound(function (Request $request) {
    Sender::SendJsonResponse(['status' => 'error', 'message' => 'Task not found.'], 404);
});