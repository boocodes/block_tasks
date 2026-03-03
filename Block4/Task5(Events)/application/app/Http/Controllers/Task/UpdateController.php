<?php

namespace Task5\App\Http\Controllers\Task;

use Task5\App\Http\Requests\Task\UpdateRequest;
use Task5\App\Http\Resources\Task\TaskResource;
use Task5\App\Models\Task;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, $task)
    {
        $task = new Task()->find($task);
        if($task['user_id'] !== $request->user()->id)
        {
            return response('', 403);
        }
        if(!$task){
            return response('', 404);
        }
        return new TaskResource($this->service->update($request, $task));
    }
}
