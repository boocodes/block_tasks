<?php

namespace Final6\App\Services;

use Final6\App\Http\Requests\Project\CreateRequest;
use Final6\App\Http\Requests\Project\UpdateRequest;
use Final6\App\Models\Project;
use Illuminate\Http\Request;

class ProjectService
{
    public function create(CreateRequest $request)
    {
        $data = $request->validated();
        $projectInstance = new Project;
        $data['owner_id'] = $request->user()->id;
        if ($projectInstance->create($data)) {
            return response('', 201);
        }

        return response('', 500);
    }

    public function delete(Request $request, $project)
    {
        $projectInstance = new Project();
        $project = $projectInstance->find($project);
        if (! $project) {
            return response('', 404);
        }
        if ($project->delete()) {
            return response('', 204);
        }

        return response('', 500);
    }

    public function update(UpdateRequest $request, $project)
    {
        $data = $request->validated();
        $projectInstance = new Project;
        $finded = $projectInstance->find($project);
        if (!$finded) {
            return response('', 404);
        }
        $result = $finded->update($data);
        var_dump($result);
        if ($result) {
            return response('', 200);
        }

        return response('', 500);
    }
}
