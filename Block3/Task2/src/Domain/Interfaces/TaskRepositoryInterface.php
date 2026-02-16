<?php

namespace Task2\Domain\Interfaces;
use Task2\Application\DTO\Task;

interface TaskRepositoryInterface
{
    public function getTaskById(int $id): Task|array;
    public function addTask(Task $task): Task;
    public function updateTask(string $id, string $title = "", string $description = ""): bool;
    public function deleteTask(string $id): bool;

}