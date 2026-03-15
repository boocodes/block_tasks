<?php

namespace Task1\App\Http\Controllers\Task;

use Task1\App\Http\Requests\Task\CreateRequest;
use Task1\App\Http\Resources\Task\TaskResource;



class CreateController extends BaseController
{
    public function __invoke(CreateRequest $request)
    {
        return $this->service->create($request);
    }
}
