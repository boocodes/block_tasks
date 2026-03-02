<?php

namespace Task4\App\Http\Controllers\Task;

use Illuminate\Http\Request;
use Task4\App\Http\Resources\Task\TaskResource;
use Task4\App\Models\Task;


class GetByIdController extends BaseController
{
    public function __invoke(Request $request, $task)
    {
        return $this->taskRepository->getById($request, $task);
    }
}
