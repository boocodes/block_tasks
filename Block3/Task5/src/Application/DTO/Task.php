<?php

namespace Task5\Application\DTO;

use Task5\Domain\Enums\StatusEnum;

class Task
{
    public int $id;
    public string $title;
    public ?string $description;
    public StatusEnum $status = StatusEnum::New;
    public string $createdAt;

    public function __construct(string $title,
                                string $description,
                                StatusEnum $status,
    )
    {
        $this->id = (int)(microtime(true) * 1000) . rand(100, 10000);
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->createdAt = new \DateTimeImmutable()->format('c');
    }
}