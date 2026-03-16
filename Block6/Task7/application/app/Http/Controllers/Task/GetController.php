<?php

namespace Task7\App\Http\Controllers\Task;

use Task7\App\Http\Controllers\Controller;
use Task7\App\Http\Requests\Task\GetRequest;
use Task7\App\Http\Controllers\Task\BaseController;


class GetController extends BaseController
{
    public function __invoke(GetRequest $request)
    {
        return $this->taskRepository->all($request);
    }
}
