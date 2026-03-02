<?php

namespace Task2\App\Http\Controllers\Task;

use Task2\App\Http\Controllers\Controller;
use Task2\App\Http\Requests\Task\GetRequest;
use Task2\App\Http\Controllers\Task\BaseController;


class GetController extends BaseController
{
    public function __invoke(GetRequest $request)
    {
        return $this->taskRepository->all($request);
    }
}
