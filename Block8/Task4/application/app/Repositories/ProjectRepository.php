<?php

namespace Final4\App\Repositories;

use Final4\App\Http\Resources\Project\ProjectResource;
use Final4\App\Models\Project;
use Final4\App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Http\Request;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function get(Request $request, $project)
    {
        $projectInstance = new Project();
        $resultProject = $projectInstance->find($project);
        if (! $resultProject) {
            return response('', 404);
        }
        
        $response = new ProjectResource($resultProject);

        return $response;
    }

    public function getAll(Request $request)
    {
        $comment = new Project();
        $resultArray = $comment->query()->orderBy('id')->get();

        return ProjectResource::collection($resultArray);
    }
}
