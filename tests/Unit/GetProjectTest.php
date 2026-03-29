<?php

namespace Tests\Unit;

use App\Http\Resources\Project\ProjectResource;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Tests\TestCase;

class GetProjectTest extends TestCase
{
    public function test_main()
    {
        $projectRepository = new ProjectRepository;
        $request = new Request;
        $project = $projectRepository->get($request, 1);
        $this->assertInstanceOf(ProjectResource::class, $project);
    }
}
