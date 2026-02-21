<?php

namespace Task7\Application\Model;


use Task7\Domain\Abstract\Model;
use Task7\Domain\Enums\TaskStatus;

class Task extends Model
{
    public string $tableName = 'tasks';
    public string $id;
    public string $title;
    public string $description;
    public TaskStatus $status;
    public array $required = ['title'];
}