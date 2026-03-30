<?php

namespace Tests\Feature;

use Final2\App\Enums\Priority;
use Final2\App\Enums\TaskStatus;
use Final2\App\Models\Task;
use DateTimeImmutable;
use Tests\TestCase;

class TaskFilterSelection extends TestCase
{
    public function test_main()
    {
        $taskInstance = new Task;
        $newTaskData =
        [
            'project_id' => 1,
            'title' => 'From feature test title',
            'description' => 'From feature test description',
            'status' => TaskStatus::BLOCKED,
            'priority' => Priority::CRITICAL,
            'due_date' => new DateTimeImmutable()->format('c'),
        ];
        $taskInstance->add($newTaskData);

    }
}
