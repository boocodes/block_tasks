<?php

namespace Task2\App\Http\Controllers\Task;

use Task2\App\Http\Requests\Task\UpdateRequest;
use Task2\App\Http\Resources\Task\TaskResource;
use Task2\App\Models\Task;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, $task)
    {
        $task = new Task()->find($task);
        if(!$task){
            return response('', 404);
        }
        if($this->service->update($request->validated(), $task))
        {
            return response('', 200);
        }
        return response('', 500);
    }
}
