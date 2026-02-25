<?php

namespace Task4\Core;


use DateTime;
use Task4\Core\Request;
use Task4\Core\Routes;
use Task4\Core\Sender;
use Task4\Infrastructure\TaskRepository;
use Task4\Application\DTO\Task;
use Task4\Domain\Enums\StatusEnum;

Routes::post('/tasks', function (Request $request) {
    $inputJson = json_decode($request->getInputData(), true);
    $query = [];
    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? '', $query);
    if ($inputJson['title'] !== '') {
        $task = new Task(
            $inputJson['title'],
            $inputJson['description'] ?? "",
            StatusEnum::New,
        );
        $taskRepository = new TaskRepository();
        $taskResult = $taskRepository->addTask($task);
        if(empty($taskResult)) {
            return;
        }
        Sender::SendJsonResponse(['id' => $taskResult['id'],
            'title' => $taskResult['title'],
            'description' => $taskResult['description'],
            'status' => $taskResult['status'],
            'createdAt' => $taskResult['created_at']], 200);
    } else {
        Sender::SendJsonResponse(['status' => 'error', 'message' => 'Can not create an task. Title is required.'], 400);
    }

});


Routes::get('/tasks', function (Request $request) {
    $taskRepository = new TaskRepository();
    $query = [];
    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? '', $query);
    $status = $query['status'] ?? '';
    $limit = isset($query['limit']) ? (int)$query['limit'] : 5;
    $cursor = $query['cursor'] ?? '';
    $getAllValue = isset($query['all']) && $query['all'] === 'true';

    if (!$getAllValue) {
        if ($limit < 1) $limit = 1;
        else if ($limit > 100) $limit = 100;

        $result = $taskRepository->getTasksWithCursorPaginationRule($status, $limit, $cursor);
        Sender::SendJsonResponse([
            'data' => $result['data'] ?? [],
            'nextCursor' => $result['cursor'] ?? null,
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
        ], 400);
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
        Sender::SendJsonResponse([
            ['status' => 'ok'],
        ], 204);
    } else {
        Sender::SendJsonResponse([
            ['status' => 'error',
                'message' => 'Task with id ' . $id . ' cannot be deleted.',],
        ], 400);
    }
});

Routes::patch('/task/{$id}', function (Request $request, $id) {
    $taskRepository = new TaskRepository();
    $inputJson = json_decode($request->getInputData(), true);
    $newTitle = $inputJson['title'] ?? null;
    $newDescription = $inputJson['description'] ?? null;
    $result = $taskRepository->updateTask($id, $newTitle, $newDescription);
    if ($result) {
        Sender::SendJsonResponse([
            ['status' => 'ok',
                'message' => 'Task with id ' . $id . ' has been updated.',],
        ], 200);
    } else {
        Sender::SendJsonResponse([
            ['status' => 'error',
                'message' => 'Task with id ' . $id . ' cannot be updated.',],
        ], 422);
    }
});

Routes::pageNotFound(function (Request $request) {
    Sender::SendJsonResponse(['status' => 'error', 'message' => 'Page not found.'], 404);
});