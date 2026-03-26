<?php 

namespace App\Services;

use App\Http\Requests\Comment\CreateRequest;
use App\Http\Requests\Comment\UpdateRequest;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentService
{
    public function create(CreateRequest $request)
    {
        $data = $request->validated();
        $commentInstance = new Comment();
        if ($commentInstance->create($data)) {
            return response('', 201);
        }
        return response('', 500);
    }
    public function delete(Request $request, $comment)
    {
        $commentInstance = new Comment();
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
        $commentInstance = new Comment();
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
