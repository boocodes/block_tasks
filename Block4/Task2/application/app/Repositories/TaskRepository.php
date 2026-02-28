<?php
namespace Task2\App\Repositories;

use Task2\App\Http\Requests\Task\GetRequest;
use Task2\App\Http\Resources\Task\TaskResource;
use Task2\App\Models\Task;
use Task2\App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{

    public function all(GetRequest $request)
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

    public function getById($task)
    {
        $result = new Task()->find($task);
        if(!$result) {
            return response('', 404);
        }
        return new TaskResource($result);
    }
}
