<?php

namespace Final3\App\Repositories;

use Final3\App\Http\Resources\Task\TaskResource;
use Final3\App\Models\Task;
use Final3\App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskRepository implements TaskRepositoryInterface
{
    public function get(Request $request, $task)
    {
        $taskInstance = new Task;
        $query = $taskInstance->query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->has('priority')) {
            $query->where('priority', $request->input('priority'));
        }
        if ($request->has('due_date')) {
            $query->where('due_date', '>=', $request->input('due_date'));
        }
        if ($request->has('search')) {
            $query->where('title', 'like', $request->input('search').'%');
        }
        $result = $query->find($task);
        if (! $result) {
            return response('', 404);
        }
        $response = new TaskResource($result);

        return $response;
    }

    public function getAll(Request $request)
    {
        $limit = $request->input('limit', 10);
        $cursor = $request->input('cursor');

        $taskInstance = new Task();

        $query = $taskInstance->query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->has('priority')) {
            $query->where('priority', $request->input('priority'));
        }
        if ($request->has('due_date')) {
            $query->where('due_date', '>=', $request->input('due_date'));
        }
        if ($request->has('search')) {
            $query->where('title', 'like', $request->input('search').'%');
        }

        if ($cursor) {
            $query->where('id', '>=', $cursor);
        }

        $tasksList = $query->limit($limit + 1)->orderBy('id')->get();
        $nextCursor = $tasksList->isNotEmpty() ? $tasksList->last()->id + 1 : null;
        $hasMoreFlag = $tasksList->count() > $limit;
        if ($hasMoreFlag) {
            $tasksList = $tasksList->slice(0, $limit);
        }

        return response()->json([
            'data' => TaskResource::collection($tasksList),
            'meta' => [
                'limit' => (int) $limit,
                'next_cursor' => $nextCursor,
                'has_more' => $hasMoreFlag,
            ],
        ]);
    }
}
