<?php

namespace Task1\App\Services\Task;



use Task1\App\Http\Requests\Task\CreateRequest;
use Task1\App\Models\Task;
use Illuminate\Http\Request;
use Task1\App\Enums\TaskStatus;

class TaskService
{
    public function create(CreateRequest $request)
    {
        $data = $request->validated();
        if(!isset($data['status'])){
            $data['status'] = TaskStatus::NEW->value;
        }
        $data['user_id'] = $request->user()->id;
        if(new Task()->create($data))
        {
            return response('', 201);
        }
        return response('', 500);
    }
    public function update(array $data, $task)
    {
        $task = new Task()->find($task);
        if(!$task){
            return response('', 404);
        }
        if($task->update($data))
        {
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
