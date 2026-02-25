<?php

namespace Task4\Domain\Interfaces;
use Task4\Application\DTO\Task;

interface TaskRepositoryInterface
{
    public function getTaskById(int $id): Task|array;
    public function addTask(Task $task, string $idempotencyKey): array;
    public function updateTask(string $id, string $title = "", string $description = ""): bool;
    public function deleteTask(string $id): bool;

}