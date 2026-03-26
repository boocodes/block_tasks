<?php 


namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class GetTasksListTest extends TestCase
{
    public function testMain()
    {
       $taskRepository = new TaskRepository();
       $request = new Request();
       $taskList = $taskRepository->getAll($request);
       $this->assertIsArray($taskList);
    }
}