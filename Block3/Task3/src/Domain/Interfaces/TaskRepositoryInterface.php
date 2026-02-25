<?php

namespace Task3\Domain\Interfaces;
use Task3\Application\DTO\Task;

interface TaskRepositoryInterface
{
    public function getTaskById(int $id): Task|array;
    public function addTask(Task $task): array;
    public function updateTask(string $id, string $title = "", string $description = ""): bool;
    public function deleteTask(string $id): bool;

}