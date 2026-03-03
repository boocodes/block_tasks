<?php

namespace Task5\App\Services\Task;



use Task5\App\Events\TaskCompletedEvent;
use Task5\App\Http\Requests\Task\CreateRequest;
use Task5\App\Http\Requests\Task\UpdateRequest;
use Task5\App\Models\Task;
use Illuminate\Http\Request;
use Task5\App\Enums\TaskStatus;

class TaskService
{
    public function create(CreateRequest $request)
    {
        $data = $request->validated();
        if(!isset($data['status'])){
            $data['status'] = TaskStatus::NEW->value;
        }
        $data['user_id'] = $request->user()->id;
        return Task::create($data);
    }
    public function update(UpdateRequest $request, Task $task)
    {
        $task->update($request->validated());
        if($task['status']->value == TaskStatus::DONE->value){
            TaskCompletedEvent::dispatch($task);
        }
        return $task;
    }
    public function delete(Request $request, $task)
    {
        $task = Task::find($task);
        if(!$task){
            return response('', 404);
        }
        if($task['user_id'] !== $request->user()->id)
        {
            return response('', 403);
        }
        $task->delete();
        return response('', 204);
    }
}
