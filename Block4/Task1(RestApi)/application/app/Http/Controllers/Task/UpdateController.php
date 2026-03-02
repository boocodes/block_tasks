<?php

namespace App\Http\Controllers\Task;

use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, $task)
    {
        $task = new Task()->find($task);
        if(!$task){
            return response('', 404);
        }
        return new TaskResource($this->service->update($request->validated(), $task));
    }
}
