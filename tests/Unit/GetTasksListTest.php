<?php


namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use App\Http\Resources\Task\TaskResource;

class GetTasksListTest extends TestCase
{
    public function testMain()
    {
        $taskRepository = new TaskRepository();
        $request = new Request();
        $taskList = $taskRepository->getAll($request);
        $taskList = $taskList->getData();
        $this->assertIsObject($taskList);
        $this->assertObjectHasProperty('data', $taskList);
        $this->assertObjectHasProperty('meta', $taskList);
        $this->assertIsArray($taskList->data);
    }
}
