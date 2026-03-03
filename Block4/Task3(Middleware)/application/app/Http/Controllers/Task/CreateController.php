<?php

namespace Task3\App\Http\Controllers\Task;

use Task3\App\Http\Resources\Task\TaskResource;
use Task3\App\Http\Controllers\Task\BaseController;
use Task3\App\Http\Requests\Task\CreateRequest;


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
