<?php

namespace Task5\Infrastructure;

use Task5\Application\DTO\Task;
use Task5\Core\Sender;
use Task5\Domain\Interfaces\TaskRepositoryInterface;
use Task5\Core\App;
use Task5\AddTask;
use Task5\GetTaskById;

class TaskRepository implements TaskRepositoryInterface
{
    private string $jsonStoragePath;
    private string $idempotencyKeyStoragePath;

    public function __construct()
    {
        $this->jsonStoragePath = dirname(__DIR__, 1) . '\database.json';
        $this->idempotencyKeyStoragePath = dirname(__DIR__, 1) . '\idempotencyKey.json';
    }

    public function getJsonStoragePath(): string
    {
        return $this->jsonStoragePath;
    }

    public function setJsonStoragePath(string $jsonStoragePath): void
    {
        $this->jsonStoragePath = $jsonStoragePath;
    }

    public function getTaskById(int $id): Task|array
    {
       return new GetTaskById()->run($id, $this->jsonStoragePath);
    }

    public function addTask(Task $task, string $idempotencyKey): array
    {
        return new AddTask()->run($task, $this->jsonStoragePath);
        $idempotencyKeysList = json_decode(file_get_contents($this->idempotencyKeyStoragePath), true);
        if ($idempotencyKeysList && strlen($idempotencyKey) > 0) {

            foreach ($idempotencyKeysList as $idempotencyKeyElem => $value) {
                if ($idempotencyKey == $value['key']) {
                    return $this->getTaskById($value['id']);
                }
            }
        }
        $data = json_decode(file_get_contents($this->getJsonStoragePath()), true);
        $data[] = [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status->value,
            'created_at' => $task->createdAt,
        ];
        file_put_contents($this->getJsonStoragePath(), json_encode($data, JSON_PRETTY_PRINT));
        $idempotencyKeysList[] = [
            'key' => $idempotencyKey,
            'id' => $task->id,
        ];
        file_put_contents($this->idempotencyKeyStoragePath, json_encode($idempotencyKeysList, JSON_PRETTY_PRINT));
        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status->value,
            'created_at' => $task->createdAt,
        ];
    }

    public function getTasks(): array
    {
        $data = file_get_contents($this->getJsonStoragePath());
        return json_decode($data, true);
    }

    public function getTasksWithCursorPaginationRule(string|null $status, int $limit, string|null $cursor): array
    {
        $taskList = $this->getTasks();

        if ($status !== null) {
            $taskList = array_filter($taskList, function ($task) use ($status) {
                return strtolower($task['status']) === strtolower($status);
            });
        }

        usort($taskList, function ($a, $b) {
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });

        if ($cursor !== null) {
            $cursorTasks = [];
            $founded = false;

            foreach ($taskList as $task) {
                if ($founded) {
                    $cursorTasks[] = $task;
                } else if (isset($task['id']) && (string)$task['id'] === (string)$cursor) {
                    $founded = true;
                }
            }
            $taskList = $cursorTasks;
            unset($task);
        }
        $data = array_slice($taskList, 0, $limit);
        $nextCursor = null;
        $hasMore = count($taskList) > $limit;
        if ($hasMore && !empty($data)) {
            $lastItem = end($data);
            $nextCursor = isset($lastItem['id']) ? (string)$lastItem['id'] : null;
        }

        return [
            'data' => $data,
            'next_cursor' => $nextCursor,
            'has_more' => $hasMore,
            'limit' => $limit,
        ];

    }

    public function updateTask(string $id, null|string $title = "", null|string $description = ""): bool
    {
        if(!$this->authBearerToken()) return false;
        $data = json_decode(file_get_contents($this->getJsonStoragePath()), true);
        foreach ($data as &$task) {
            if ($task['id'] == $id) {
                $task['title'] = $title ?? $task['title'];
                $task['description'] = $description ?? $task['description'];
                file_put_contents($this->getJsonStoragePath(), json_encode($data));
                return true;
            }
        }
        unset($task);
        return false;
    }

    public function deleteTask(string $id): bool
    {
        if(!$this->authBearerToken()) return false;
        $data = json_decode(file_get_contents($this->getJsonStoragePath()), true);
        $previousSize = count($data);
        foreach ($data as $task => $value) {
            if ($value['id'] == $id) {
                unset($data[$task]);
            }
        }
        unset($task);
        if ($previousSize > count($data)) {
            file_put_contents($this->getJsonStoragePath(), json_encode($data, JSON_PRETTY_PRINT));
            return true;
        } else {
            return false;
        }

    }
}