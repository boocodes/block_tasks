<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\CreateRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function get(Request $request, $task)
    {
        return $this->taskRepository->get($request, $task);
    }
    public function getAll(Request $request)
    {
       return $this->taskRepository->getAll($request);
    }
    public function add(CreateRequest $request)
    {
        return $this->taskService->create($request);
    }
    public function update(UpdateRequest $request, $task)
    {
       return $this->taskService->update($request, $task);
    }
    public function delete(Request $request, $task)
    {
       return $this->taskService->delete($request, $task);
    }
}
