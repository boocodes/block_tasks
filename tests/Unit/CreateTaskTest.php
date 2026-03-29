<?php


namespace Tests\Unit;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Tests\TestCase;
use App\Services\TaskService;
use App\Http\Requests\Task\CreateRequest;
use DateTimeImmutable;
use Illuminate\Container\Container;

class CreateTaskTest extends TestCase
{
    public function testMain()
    {
        $taskService = new TaskService();
        $newTaskData =
            [
                'title' => 'test',
                'description' => 'Test value',
                'status' => TaskStatus::NEW->value,
                'priority' => Priority::NORMAL->value,
                'due_date' => new DateTimeImmutable()->format('c'),
                'project_id' => '1'
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
