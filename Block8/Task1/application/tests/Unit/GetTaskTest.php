<?php 


namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;



class GetTaskTest extends TestCase
{
    public function testMain()
    {
       $taskRepository = new TaskRepository();
       $request = new Request();
       $task = $taskRepository->get($request, 1);
       $this->assertIsArray($task);
    }
}