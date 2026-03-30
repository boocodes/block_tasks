<?php

namespace Tests\Unit;

use Final2\App\Http\Resources\Project\ProjectResource;
use Final2\App\Repositories\ProjectRepository;
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
