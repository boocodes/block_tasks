<?php

namespace Tests\Unit;

use App\Http\Resources\Project\ProjectResource;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Tests\TestCase;

class GetProjectsListTest extends TestCase
{
    public function test_main()
    {
        $projectRepository = new ProjectRepository;
        $request = new Request;
        $projectList = $projectRepository->getAll($request);
        $this->assertInstanceOf(ProjectResource::class, $projectList[0]);
    }
}
