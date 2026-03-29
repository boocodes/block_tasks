<?php 


namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use App\Http\Resources\Project\ProjectResource;

class GetProjectsListTest extends TestCase
{
    public function testMain()
    {
       $projectRepository = new ProjectRepository();
       $request = new Request();
       $projectList = $projectRepository->getAll($request);
       $this->assertInstanceOf(ProjectResource::class, $projectList[0]);
    }
}