<?php

namespace Tests\Feature;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Tests\TestCase;
use App\Models\Task;
use DateTimeImmutable;


class TaskFilterSelection extends TestCase
{
    public function testMain()
    {
        $taskInstance = new Task();
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