<?php

namespace App\Http\Controllers\Task;


use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class GetByIdController extends BaseController
{
    public function __invoke(Request $request, $task)
    {
        $result = $this->service->getById($request, $task);
        if(!$result)
        {
            return response('', 404);
        }
        return new TaskResource($result);
    }
}
