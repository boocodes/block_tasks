<?php 


namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;


class GetProjectsListTest extends TestCase
{
    public function testMain()
    {
       $projectRepository = new ProjectRepository();
       $request = new Request();
       $projectList = $projectRepository->getAll($request);
       $this->assertIsArray($projectList);
    }
}