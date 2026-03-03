<?php

namespace Task2\App\Http\Controllers\Task;

use Task2\App\Http\Requests\Task\CreateRequest;
use Task2\App\Http\Resources\Task\TaskResource;
use Task2\App\Http\Controllers\Task\BaseController;


class CreateController extends BaseController
{
    public function __invoke(CreateRequest $request)
    {
        if($this->service->create($request))
        {
            return response('', 201);
        }
        return response('', 500);
    }
}

