<?php

namespace Task6\App\Services\Task;


use Task6\App\Events\TaskCompletedEvent;
use Task6\App\Http\Requests\Task\CreateRequest;
use Task6\App\Http\Requests\Task\UpdateRequest;
use Task6\App\Models\Task;
use Illuminate\Http\Request;
use Task6\App\Enums\TaskStatus;

class TaskService
{
    public function create(CreateRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['status'])) {
            $data['status'] = TaskStatus::NEW->value;
        }
        $data['user_id'] = $request->user()->id;
        if (new Task()->create($data)) {
            return response('', 201);
        }
        return response('', 500);
    }

    public function update(array $data, $task)
    {
        $task = new Task()->find($task);
        if (!$task) {
            return response('', 404);
        }
        $previousStatus = $task->status;
        $result = $task->update($data);
        if (TaskStatus::DONE->value !== $previousStatus->value && $task->status->value === TaskStatus::DONE->value) {
            TaskCompletedEvent::dispatch($task, $previousStatus);
        }
        if ($result) {
            return response('', 200);
        }

        return response('', 500);
    }

    public function delete(Request $request, $task)
    {
        $task = Task::find($task);
        $task->delete();
        return response('', 204);
    }
}
