<?php

namespace Task5\App\Services\Task;



use Task5\App\Enums\OutboxEventsStatus;
use Task5\App\Events\TaskCompleted;
use Task5\App\Http\Requests\Task\CreateRequest;
use Task5\App\Jobs\ProcessOutboxJob;
use Task5\App\Models\OutboxEvents;
use Task5\App\Models\Task;
use Illuminate\Http\Request;
use Task5\App\Enums\TaskStatus;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function create(CreateRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['status'])) {
            $data['status'] = TaskStatus::NEW ->value;
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
        $shouldDispatchFlag = false;
        $result = 0;
        $previousStatus = $task->status;
        DB::transaction(function () use ($task, $data, $previousStatus, &$result, &$shouldDispatchFlag) {
            $result = $task->update($data);
            if (TaskStatus::DONE->value !== $previousStatus->value && $task->status->value === TaskStatus::DONE->value) {
                new OutboxEvents()::create(
                    [
                        'type' => 'TaskCompleted',
                        'payload' => [
                            'taskId' => $task->id,
                            'userId' => $task->user_id,
                            'previousStatus' => $previousStatus,
                        ],
                        'status' => OutboxEventsStatus::NEW ,
                        'attempts' => 0,
                    ]
                );
                $shouldDispatchFlag = true;
            }
        });

        if ($shouldDispatchFlag) {
            ProcessOutboxJob::dispatch()->onQueue('outbox');
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
