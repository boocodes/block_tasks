<?php

namespace Final7\App\Repositories;

use Final7\App\Http\Resources\Task\TaskResource;
use Final7\App\Models\Task;
use Final7\App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskRepository implements TaskRepositoryInterface
{
    public function get(Request $request, $task)
    {
        $taskInstance = Task::with(['project', 'comments', 'comments.user']);
        $result = $taskInstance->find($task);
        if (!$result) {
            return response('', 404);
        }
        return new TaskResource($result);
    }

    public function getAll(Request $request)
    {
        $limit = (int)$request->input('limit', 10);
        $cursor = $request->input('cursor');



        $query = Task::query()
            ->with(['project', 'comments'])
            ->orderBy('id', 'asc');

        if ($cursor !== null && is_numeric($cursor) && (int) $cursor > 0) {
            $query->where('id', '>', (int)$cursor);
        }
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
            $query->where('title', 'like', $request->input('search') . '%');
        }
        if ($request->has('project_id')) {
            $query->where('project_id', $request->input('project_id'));
        }
        $tasks = $query->limit($limit + 1)->get();
        $hasMore = $tasks->count() > $limit;
        if ($hasMore) {
            $tasks = $tasks->slice(0, $limit);
        }
        $nextCursor = $hasMore && $tasks->isNotEmpty() ? $tasks->last()->id : null;

        return response()->json([
            'data' => TaskResource::collection($tasks),
            'meta' => [
                'limit' => $limit,
                'next_cursor' => $nextCursor,
                'has_more' => $hasMore
            ]
        ]);
    }
}
