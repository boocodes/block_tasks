<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Resources\Project\ProjectResource;
use App\Repositories\Interfaces\CrudRepositoryInterface;

class ProjectRepository extends CrudRepositoryInterface
{
    public function get(Request $request, $id)
    {
        $result = new Project();
        $result = $result->find($id);
        if (!$result) {
            return response('', 404);
        }
        $response = new ProjectResource($result);
        return $response;
    }
    public function getAll(Request $request)
    {
        $comment = new Project();
        $resultArray = $comment->query()->orderBy('id')->get();
        return ProjectResource::collection($resultArray);
    }
}
