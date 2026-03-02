<?php

namespace Task2\App\Http\Controllers\Task;

use Illuminate\Http\Request;
use Task2\App\Http\Resources\Task\TaskResource;
use Task2\App\Models\Task;


class GetByIdController extends BaseController
{
    public function __invoke(Request $request, $task)
    {
        return $this->taskRepository->getById($task);
    }
}
