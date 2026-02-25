<?php

namespace Task2\Domain\Interfaces;
use Task2\Application\DTO\Task;
use Task2\Domain\Enums\StatusEnum;

interface TaskRepositoryInterface
{
    public function getTaskById(int $id): Task|array;
    public function addTask(Task $task): Task;
    public function updateTask(string $id, ?string $title = null, ?string $description = null, ?StatusEnum $enum = null): array;
    public function deleteTask(string $id): bool;

}