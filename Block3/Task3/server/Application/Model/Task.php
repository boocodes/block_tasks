<?php

namespace Task3\Application\Model;
use Task3\Domain\Abstract\Model;
use Task3\Domain\Enums\TaskStatus;


class Task extends Model
{
    public string $tableName = 'tasks';
    public int $id;
    public string $title;
    public string $description = "Default description to task";
    public TaskStatus $status = TaskStatus::NEW;
    public string $createdAt;
    public array $required = ['title'];
}