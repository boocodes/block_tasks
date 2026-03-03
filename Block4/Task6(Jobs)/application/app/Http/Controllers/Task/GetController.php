<?php

namespace Task6\App\Http\Controllers\Task;

use Task6\App\Http\Controllers\Controller;
use Task6\App\Http\Requests\Task\GetRequest;
use Task6\App\Http\Controllers\Task\BaseController;


class GetController extends BaseController
{
    public function __invoke(GetRequest $request)
    {
        return $this->taskRepository->all($request);
    }
}
