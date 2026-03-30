<?php

namespace App\Services;

use App\Http\Requests\Task\CreateRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskService
{
    public function create(CreateRequest $request)
    {
        $data = $request->validated();
        $taskInstance = new Task();
        if ($taskInstance->create($data)) {
            return response('', 201);
        }

        return response('', 500);
    }

    public function delete(Request $request, $task)
    {
        $taskInstance = new Task();
        $taskResult = $taskInstance->find($task);
        if (! $taskResult) {
            return response('', 404);
        }
        if ($taskResult->delete()) {
            return response('', 204);
        }

        return response('', 500);
    }

    public function update(UpdateRequest $request, $task)
    {
        $data = $request->validated();
        $taskInstance = new Task();
        $finded = $taskInstance->find($task);
        if (! $finded) {
            return response('', 404);
        }
        $result = $finded->update($data);
        if ($result) {
            return response('', 200);
        }

        return response('', 500);
    }
}
