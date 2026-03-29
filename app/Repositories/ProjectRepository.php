<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Resources\Project\ProjectResource;
use App\Repositories\Interfaces\CrudRepositoryInterface;

class ProjectRepository implements CrudRepositoryInterface
{
    public function get(Request $request, $id)
    {
        $projectInstance = new Project();
        $resultProject = $projectInstance->find($id);
        if (!$resultProject) {
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
