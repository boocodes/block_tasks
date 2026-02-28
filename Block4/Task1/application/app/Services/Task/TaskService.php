<?php

namespace App\Services\Task;



use App\Http\Requests\Task\CreateRequest;
use App\Http\Requests\Task\GetRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Mockery\Exception;

class TaskService
{
    public function create(CreateRequest $request)
    {
        $data = $request->validated();
        if(!isset($data['status'])){
            $data['status'] = \App\Enums\Task::NEW->value;
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
    public function getAll(GetRequest $request)
    {
        $query = new Task()->query();
        $limit = $request->input('limit', 10);
        if($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if($request->filled('cursor')) {
            $query->where('id', '>=', $request->cursor);
        }

        $itemsResult = $query->orderBy('id')->take($limit + 1)->get();

        $hasMoreData = $itemsResult->count() > $limit;
        $slicedResult = $hasMoreData ? $itemsResult->slice(0, $limit++) : $itemsResult;

        $nextCursor = $hasMoreData ? $itemsResult->last()->id : null;
        $previousCursor = $request->filled('cursor') ? $request->cursor : null;

        return response()->json([
            'data' => $slicedResult,
            'pagination' => [
                'next_cursor' => $nextCursor,
                'prev_cursor' => $previousCursor,
                'has_more_pages' => $hasMoreData,
                'per_page' => $limit,
            ]
        ], 200);

    }
    public function getById(Request $request, $task)
    {
        return new Task()->find($task);
    }
}
