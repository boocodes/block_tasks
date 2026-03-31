<?php

namespace Final5\App\Services;

use Final5\App\Enums\TaskStatus;
use Final5\App\Events\TaskCompletedEvent;
use Final5\App\Events\TaskCreatedEvent;
use Final5\App\Events\TaskStatusChangedEvent;
use Final5\App\Http\Requests\Task\CreateRequest;
use Final5\App\Http\Requests\Task\UpdateRequest;
use Final5\App\Models\Task;
use Illuminate\Http\Request;

class TaskService
{
    public function create(CreateRequest $request)
    {
        $data = $request->validated();
        $taskInstance = new Task();
        $result = $taskInstance->create($data);
        if ($result) {
            TaskCreatedEvent::dispatch($result, $request->user()->id);
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
        $initialTaskStatus = $finded->status->value;
        if (! $finded) {
            return response('', 404);
        }
        $result = $finded->update($data);
        $idempotencyKey = $request->header('Idempotency-key') ?? uniqid();
        if ($initialTaskStatus !== $finded->status->value) {
            if ($initialTaskStatus !== TaskStatus::DONE->value && $finded->status->value === TaskStatus::DONE->value) {
                TaskCompletedEvent::dispatch($finded, $request->user()->id, $idempotencyKey);
            } else {
                TaskStatusChangedEvent::dispatch($finded, $initialTaskStatus, $request->user()->id, $idempotencyKey);
            }
        }

        if ($result) {
            return response('', 200);
        }

        return response('', 500);
    }
}
