<?php

namespace App\Http\Controllers\Task;

use App\Http\Requests\Task\CreateRequest;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Task\BaseController;

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
