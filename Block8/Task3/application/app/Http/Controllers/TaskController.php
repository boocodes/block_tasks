<?php

namespace Final3\App\Http\Controllers;

use Final3\App\Http\Requests\Task\CreateRequest;
use Final3\App\Http\Requests\Task\UpdateRequest;
use Final3\App\Models\Task;
use Final3\App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function get(Request $request, $task)
    {
        $taskInstance = new Task();
        $this->authorize('view', $taskInstance->find($task));
        return $this->taskRepository->get($request, $task);
    }

    public function getAll(Request $request)
    {
        $this->authorize('viewAny', [Task::class, User::class]);
        return $this->taskRepository->getAll($request);
    }

    public function add(CreateRequest $request)
    {
        $this->authorize('create', [Task::class, User::class]);
        return $this->taskService->create($request);
    }

    public function update(UpdateRequest $request, $task)
    {
        $taskInstance = new Task();
        $this->authorize('update', $taskInstance->find($task));
        return $this->taskService->update($request, $task);
    }

    public function delete(Request $request, $task)
    {
        $taskInstance = new Task();
        $this->authorize('delete', $taskInstance->find($task));
        return $this->taskService->delete($request, $task);
    }
}
