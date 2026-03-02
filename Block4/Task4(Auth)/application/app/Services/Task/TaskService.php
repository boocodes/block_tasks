<?php

namespace Task4\App\Services\Task;



use Task4\App\Http\Requests\Task\CreateRequest;
use Task4\App\Models\Task;
use Illuminate\Http\Request;
use Task4\App\Enums\TaskStatus;

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
        if($task['user_id'] !== $request->user()->id)
        {
            return response('', 403);
        }
        $task->delete();
        return response('', 204);
    }
}
