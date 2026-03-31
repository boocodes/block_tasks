<?php

namespace Final6\App\Repositories;

use Final6\App\Http\Resources\Project\ProjectResource;
use Final6\App\Models\Project;
use Final6\App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Http\Request;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function get(Request $request, $project)
    {
        $projectInstance = new Project();
        $resultProject = $projectInstance->with(['user', 'tasks'])->find($project);
        if (! $resultProject) {
            return response('', 404);
        }
        
        $response = new ProjectResource($resultProject);

        return $response;
    }

    public function getAll(Request $request)
    {
        $projects = Project::query()
            ->with(['user', 'tasks'])
            ->orderBy('id')
            ->get();
        return ProjectResource::collection($projects);
    }
}
