<?php

namespace Tests\Unit;

use Final2\App\Enums\Priority;
use Final2\App\Enums\TaskStatus;
use Final2\App\Http\Requests\Task\CreateRequest;
use Final2\App\Services\TaskService;
use DateTimeImmutable;
use Illuminate\Container\Container;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    public function test_main()
    {
        $taskService = new TaskService;
        $newTaskData =
            [
                'title' => 'test',
                'description' => 'Test value',
                'status' => TaskStatus::NEW->value,
                'priority' => Priority::NORMAL->value,
                'due_date' => new DateTimeImmutable()->format('c'),
                'project_id' => '1',
            ];
        $request = CreateRequest::create('/tasks', 'POST', $newTaskData);
        $request->setContainer(Container::getInstance());
        $request->setRedirector(app('redirect'));
        $request->validateResolved();
        $result = $taskService->create($request);
        $this->assertEquals($result->getStatusCode(), 201);
        $this->assertEquals($result->getContent(), '');
    }
}
