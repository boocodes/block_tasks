<?php

namespace Task5\App\Http\Controllers\Task;

use Illuminate\Http\Request;
use Task5\App\Http\Resources\Task\TaskResource;
use Task5\App\Models\Task;


class GetByIdController extends BaseController
{
    public function __invoke(Request $request, $task)
    {
        $this->authorize('view', new Task()->find($task));
        return $this->taskRepository->getById($request, $task);
    }
}
