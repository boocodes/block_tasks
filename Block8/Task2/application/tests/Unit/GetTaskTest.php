<?php

namespace Tests\Unit;

use Final2\App\Http\Resources\Task\TaskResource;
use Final2\App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Tests\TestCase;

class GetTaskTest extends TestCase
{
    public function test_main()
    {
        $taskRepository = new TaskRepository;
        $request = new Request;
        $task = $taskRepository->get($request, 1);
        $this->assertInstanceOf(TaskResource::class, $task);
    }
}
