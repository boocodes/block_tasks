<?php

namespace App\Http\Controllers\Task;

use App\Http\Requests\Task\CreateRequest;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class CreateController extends BaseController
{
    public function __invoke(CreateRequest $request)
    {
        return new TaskResource($this->service->create($request));
    }
}
