<?php

namespace Tests\Unit;

use App\Http\Resources\Task\TaskResource;
use App\Repositories\TaskRepository;
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
