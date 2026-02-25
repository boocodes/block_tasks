<?php

namespace Task4\Infrastructure;

use Task4\Application\DTO\Task;
use Task4\Core\Sender;
use Task4\Domain\Interfaces\TaskRepositoryInterface;
use Task4\Core\App;
use Task4\Infrastructure\middleware\Auth;

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
        $data = file_get_contents($this->getJsonStoragePath());
        $data = json_decode($data, true);
        foreach ($data as $task) {
            if ($task['id'] == $id) {
                return $task;
            }
        }
        unset($task);
        return [];
    }
    public function addTaskWithIdempotencyKey(Task $task): array
    {
        if(!isset($_SERVER['HTTP_IDEMPOTENCY_KEY'])) {
            return $this->addTask($task);
        }

        $currentIdempotencyKey = $_SERVER['HTTP_IDEMPOTENCY_KEY'];
        $previousIdempotencyKeys = file_get_contents($this->idempotencyKeyStoragePath);
        if(!$previousIdempotencyKeys)
        {
            $newIdempotencyValue = [
                'task_id' => $task->id,
                'idempotency_key' => $currentIdempotencyKey,
            ];
            file_put_contents($this->idempotencyKeyStoragePath, json_encode([$newIdempotencyValue], JSON_PRETTY_PRINT));
            return $this->addTask($task);
        }
        $previousIdempotencyKeys = json_decode($previousIdempotencyKeys, true);
        foreach ($previousIdempotencyKeys as $key => $value)
        {
            if($value['idempotency_key'] === $currentIdempotencyKey)
            {
                // search task
                $searchingTask = $this->getTaskById($value['task_id']);
                if(empty($searchingTask))
                {
                    $newTask = $this->addTask($task);
                    // delete idempotency key
                    $previousIdempotencyKeys[$key]['task_id'] = $newTask['id'];
                    file_put_contents($this->idempotencyKeyStoragePath, json_encode($previousIdempotencyKeys, JSON_PRETTY_PRINT));

                    return $newTask;
                }
                else
                {
                    if(
                        $searchingTask['title'] === $task->title &&
                        $searchingTask['description'] === $task->description &&
                        $searchingTask['status'] === $task->status->value
                    )
                    {
                        $task->id = $searchingTask['id'];
                        return $task->toArray();
                    }
                    else
                    {
                        return [];
                    }

                }
            }
        }
        // nothing found
        $newIdempotencyValue = [
            'task_id' => $task->id,
            'idempotency_key' => $currentIdempotencyKey,
        ];
        $previousIdempotencyKeys[] = $newIdempotencyValue;
        file_put_contents($this->idempotencyKeysPath, json_encode($previousIdempotencyKeys, JSON_PRETTY_PRINT));
        return $this->addTask($task);
    }
    public function addTask(Task $task): array
    {
        $data = json_decode(file_get_contents($this->getJsonStoragePath()), true);
        $newTask = [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status->value,
            'created_at' => $task->createdAt,
        ];
        $data[] = $newTask;
        file_put_contents($this->getJsonStoragePath(), json_encode($data, JSON_PRETTY_PRINT));
        return $newTask;
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
        if(!Auth::auth()) return false;
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
        if(!Auth::auth()) return false;
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