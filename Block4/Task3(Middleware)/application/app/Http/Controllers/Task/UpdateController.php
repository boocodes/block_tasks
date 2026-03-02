<?php

namespace Task3\App\Http\Controllers\Task;

use Task3\App\Http\Requests\Task\UpdateRequest;
use Task3\App\Http\Resources\Task\TaskResource;
use Task3\App\Models\Task;

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
