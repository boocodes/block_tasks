<?php

namespace Final3\App\Http\Controllers;

use Final3\App\Http\Requests\Project\CreateRequest;
use Final3\App\Http\Requests\Project\UpdateRequest;
use Final3\App\Models\Project;
use Final3\App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function get(Request $request, $project)
    {
        $projectInstance = new Project();
        $this->authorize('view', $projectInstance->find($project));
        return $this->projectRepository->get($request, $project);
    }

    public function getAll(Request $request)
    {
        $this->authorize('viewAny', [Project::class, User::class]);
        return $this->projectRepository->getAll($request);
    }

    public function add(CreateRequest $request)
    {
        $this->authorize('create', [Project::class, User::class]);
        return $this->projectService->create($request);
    }

    public function update(UpdateRequest $request, $project)
    {
        $projectInstance = new Project();
        $this->authorize('update', $projectInstance->find($project));
        return $this->projectService->update($request, $project);
    }

    public function delete(Request $request, $project)
    {
        $projectInstance = new Project();
        $this->authorize('delete', $projectInstance->find($project));
        return $this->projectService->delete($request, $project);
    }
}
