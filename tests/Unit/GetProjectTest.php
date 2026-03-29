<?php 


namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use App\Http\Resources\Project\ProjectResource;

class GetProjectTest extends TestCase
{
    public function testMain()
    {
       $projectRepository = new ProjectRepository();
       $request = new Request();
       $project = $projectRepository->get($request, 1);
       $this->assertInstanceOf(ProjectResource::class, $project);
    }
}