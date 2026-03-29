<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\CreateRequest;
use App\Http\Requests\Project\UpdateRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function get(Request $request, $project)
    {
        return $this->projectRepository->get($request, $project);
    }
    public function getAll(Request $request)
    {
        return $this->projectRepository->getAll($request);
    }
    public function add(CreateRequest $request)
    {
        return $this->projectService->create($request);
    }
    public function update(UpdateRequest $request, $project)
    {
       return $this->projectService->update($request, $project);
    }
    public function delete(Request $request, $project)
    {
        return $this->projectService->delete($request, $project);
    }
}
