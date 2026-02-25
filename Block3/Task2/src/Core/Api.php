<?php

namespace Task2\Core;

use Task2\Domain\Enums\StatusEnum;
use Task2\Infrastructure\TaskRepository;
use Task2\Application\DTO\Task;

Routes::post('/tasks', function (Request $request) {
    $inputJson = json_decode($request->getInputData(), true);
    if ($inputJson['title'] !== '') {
        $task = new Task(
            $inputJson['title'],
            $inputJson['description'] ?? "",
            isset($inputJson['status']) ? (StatusEnum::tryFrom($inputJson['status']) ?? StatusEnum::New) : StatusEnum::New
        );
        $taskRepository = new TaskRepository();
        $taskRepository->addTask($task);

        Sender::SendJsonResponse(['id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status,
            'createdAt' => $task->createdAt], 201);
    } else {
        Sender::SendJsonResponse(['status' => 'error', 'message' => 'Can not create an task. Title is required.'], 400);
    }

});


Routes::get('/tasks', function (Request $request) {
    $taskRepository = new TaskRepository();
    $query = [];
    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? '', $query);
    $status = $query['status'] ?? null;
    $limit = isset($query['limit']) ? (int)$query['limit'] : 5;
    $cursor = $query['cursor'] ?? null;
    $getAllValue = isset($query['all']) && $query['all'] === 'true';

    if (!$getAllValue) {
        if ($limit < 1) $limit = 1;
        else if ($limit > 100) $limit = 100;

        $result = $taskRepository->getTasksWithCursorPaginationRule($status, $limit, $cursor);
        Sender::SendJsonResponse([
            'data' => $result['data'] ?? [],
            'nextCursor' => $result['next_cursor'] ?? null,
        ], 200);
    } else {
        $taskList = $taskRepository->getTasks();
        usort($taskList, function ($a, $b) {
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });
        Sender::SendJsonResponse([
            'data' => $taskList,
        ], 200);
    }
});

Routes::get('/task/{$id}', function (Request $request, $id) {
    $taskRepository = new TaskRepository();
    $task = $taskRepository->getTaskById($id);
    if (empty($task)) {
        Sender::SendJsonResponse([
            'status' => 'error',
            'message' => 'Task with id ' . $id . ' not found.',
        ], 404);
    } else {
        Sender::SendJsonResponse([
            ['data' => $task]
        ], 200);
    }
});

Routes::delete('/task/{$id}', function (Request $request, $id) {
    $taskRepository = new TaskRepository();
    $result = $taskRepository->deleteTask($id);
    if ($result) {
        http_response_code(204);
    } else {
        http_response_code(404);
    }
});

Routes::patch('/task/{$id}', function (Request $request, $id) {
    $taskRepository = new TaskRepository();
    $inputJson = json_decode($request->getInputData(), true);
    $newTitle = $inputJson['title'] ?? null;
    $newDescription = $inputJson['description'] ?? null;
    $newStatus =  isset($inputJson['status']) ? (StatusEnum::tryFrom($inputJson['status'])) : null;
    $result = $taskRepository->updateTask($id, $newTitle, $newDescription, $newStatus);
    if (!empty($result)) {
        Sender::SendJsonResponse($result, 200);
    } else {
        Sender::SendJsonResponse([], 422);
    }
});

Routes::pageNotFound(function (Request $request) {
    Sender::SendJsonResponse(['status' => 'error', 'message' => 'Task not found.'], 404);
});