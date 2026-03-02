<?php

namespace Task5\App\Http\Controllers\Task;

use Task5\App\Http\Controllers\Controller;
use Task5\App\Http\Requests\Task\GetRequest;
use Task5\App\Http\Controllers\Task\BaseController;


class GetController extends BaseController
{
    public function __invoke(GetRequest $request)
    {
        return $this->taskRepository->all($request);
    }
}
