<?php

namespace Task2\Infrastructure;

use Task2\Application\DTO\Task;
use Task2\Domain\Interfaces\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    private string $jsonStoragePath;

    public function __construct()
    {
        $this->jsonStoragePath = dirname(__DIR__, 1) . '\database.json';
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
            if ($task['id'] === $id) {
                return $task;
            }
        }
        unset($task);
        return [];
    }

    public function addTask(Task $task): Task
    {
        $data = json_decode(file_get_contents($this->getJsonStoragePath()), true);
        $data[] = [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status->value,
            'created_at' => $task->createdAt,
        ];
        file_put_contents($this->getJsonStoragePath(), json_encode($data, JSON_PRETTY_PRINT));
        return $task;
    }

    public function getTasks(): array
    {
        $data = file_get_contents($this->getJsonStoragePath());
        return json_decode($data, true);
    }

    public function updateTask(string $id, string $title = "", string $description = ""): bool
    {
        $data = json_decode(file_get_contents($this->getJsonStoragePath()), true);
        foreach ($data as $task) {
            if ($task['id'] === $id) {
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
        $data = json_decode(file_get_contents($this->getJsonStoragePath()), true);
        foreach ($data as $task) {
            if ($task['id'] === $id) {
                unset($task);
            }
            file_put_contents($this->getJsonStoragePath(), json_encode($data));
            return true;
        }
        unset($task);
        return false;
    }
}