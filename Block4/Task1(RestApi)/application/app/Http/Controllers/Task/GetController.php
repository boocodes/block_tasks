<?php

namespace App\Http\Controllers\Task;

use App\Http\Requests\Task\CreateRequest;
use App\Http\Requests\Task\GetRequest;
use App\Http\Resources\Task\TaskResource;
use Illuminate\Http\Request;
use App\Models\Task;

class GetController extends BaseController
{
    public function __invoke(GetRequest $request)
    {
        return $this->service->getAll($request);
    }
}
