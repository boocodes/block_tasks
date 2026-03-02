<?php

namespace Task4\App\Http\Controllers\Task;

use Task4\App\Http\Requests\Task\CreateRequest;
use Task4\App\Http\Resources\Task\TaskResource;



class CreateController extends BaseController
{
    public function __invoke(CreateRequest $request)
    {
        return new TaskResource($this->service->create($request));
    }
}
