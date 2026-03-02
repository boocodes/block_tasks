<?php

namespace Task3\App\Services\Task;



use Task3\App\Http\Requests\Task\CreateRequest;
use Task3\App\Models\Task;
use Illuminate\Http\Request;
use Task3\App\Enums\TaskStatus;

class TaskService
{
    public function create(CreateRequest $request)
    {
        $data = $request->validated();
        if(!isset($data['status'])){
            $data['status'] = TaskStatus::NEW->value;
        }
        return Task::create($data);
    }
    public function update(array $data, Task $task)
    {
        $task->update($data);
        return $task;
    }
    public function delete(Request $request, $task)
    {
        $task = Task::find($task);
        if(!$task){
            return response('', 404);
        }
        $task->delete();
        return response('', 204);
    }
}
