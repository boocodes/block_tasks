<?php

namespace Task5\Application\DTO;

use Task5\Domain\Enums\StatusEnum;

class Task extends Model
{
    protected int $id;
    protected string $title;
    protected ?string $description;
    protected StatusEnum $status = StatusEnum::New;
    protected string $createdAt;
    protected string $tableName = 'Tasks';
    protected array $required = ['id', 'title', 'description'];

    private function __construct()
    {
    }

}