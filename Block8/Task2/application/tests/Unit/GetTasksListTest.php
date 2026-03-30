<?php

namespace Tests\Unit;

use Final2\App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Tests\TestCase;

class GetTasksListTest extends TestCase
{
    public function test_main()
    {
        $taskRepository = new TaskRepository;
        $request = new Request;
        $taskList = $taskRepository->getAll($request);
        $taskList = $taskList->getData();
        $this->assertIsObject($taskList);
        $this->assertObjectHasProperty('data', $taskList);
        $this->assertObjectHasProperty('meta', $taskList);
        $this->assertIsArray($taskList->data);
    }
}
