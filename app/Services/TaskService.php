<?php 

namespace App\Services;

use App\Http\Requests\Task\CreateRequest;
use App\Http\Requests\Task\UpdateRequest;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskService
{
    public function create(CreateRequest $request)
    {
        $data = $request->validated();
        $commentInstance = new Task();
        if ($commentInstance->create($data)) {
            return response('', 201);
        }
        return response('', 500);
    }
    public function delete(Request $request, $comment)
    {
        $commentInstance = new Task();
        $comment = $commentInstance->find($comment);
        if (!$comment) {
            return response('', 404);
        }
        if ($comment->delete()) {
            return response('', 204);
        }
        return response('', 500);
    }
    public function update(UpdateRequest $request, $comment)
    {
        $data = $request->validated();
        $commentInstance = new Task();
        $commentInstance->find($comment);
        if (!$commentInstance) {
            return response('', 404);
        }
        $result = $commentInstance->update($data);
        if ($result) {
            return response('', 200);
        }
        return response('', 500);
    }
}
