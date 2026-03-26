<?php


namespace Tests\Unit;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Tests\TestCase;
use App\Services\TaskService;
use App\Http\Requests\Task\CreateRequest;
use DateTimeImmutable;


class CreateTaskTest extends TestCase
{
    public function testMain()
    {
        $taskService = new TaskService();
        $newCommentData =
            [
                'title' => 'test',
                'description' => 'Test value',
                'status' => TaskStatus::NEW->value,
                'priority' => Priority::NORMAL->value,
                'due_data' => new DateTimeImmutable()->format('c'),
            ];
        $request = new CreateRequest($newCommentData);
        $result = $taskService->create($request);
        $this->assertEquals($result->getStatusCode(), 201);
        $this->assertEquals($result->getContent(), '');
    }
}
