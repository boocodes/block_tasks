<?php

namespace Task5\Application\Model;


use Task5\Domain\Abstract\Model;
use Task5\Domain\Enums\TaskStatus;

class Task extends Model
{
    public string $tableName = 'tasks';
    public string $id;
    public string $title;
    public string $description;
    public string $createdAt;
    public TaskStatus $status;
    public array $required = ['title'];
}