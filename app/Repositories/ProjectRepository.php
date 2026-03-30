<?php

namespace App\Repositories;

use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
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
