<?php

namespace Task6\App\Http\Controllers\Task;

use Task6\App\Http\Requests\Task\CreateRequest;
use Task6\App\Http\Resources\Task\TaskResource;



class CreateController extends BaseController
{
    public function __invoke(CreateRequest $request)
    {
        return new TaskResource($this->service->create($request));
    }
}
