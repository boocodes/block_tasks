<?php

namespace Task3\App\Http\Controllers\Task;

use Illuminate\Http\Request;
use Task3\App\Http\Resources\Task\TaskResource;
use Task3\App\Models\Task;


class GetByIdController extends BaseController
{
    public function __invoke(Request $request, $task)
    {
        return $this->taskRepository->getById($request, $task);
    }
}
