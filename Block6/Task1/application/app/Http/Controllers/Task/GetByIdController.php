<?php

namespace Task1\App\Http\Controllers\Task;

use Illuminate\Http\Request;
use Task1\App\Http\Resources\Task\TaskResource;
use Task1\App\Models\Task;


class GetByIdController extends BaseController
{
    public function __invoke(Request $request, $task)
    {
        $this->authorize('view', new Task()->find($task));
        return $this->taskRepository->getById($request, $task);
    }
}
