<?php

namespace Final6\App\Services;

use Final6\App\Http\Requests\Comment\CreateRequest;
use Final6\App\Http\Requests\Comment\UpdateRequest;
use Final6\App\Models\Comment;
use Illuminate\Http\Request;

class CommentService
{
    public function create(CreateRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $commentInstance = new Comment;
        if ($commentInstance->create($data)) {
            return response('', 201);
        }

        return response('', 500);
    }

    public function delete(Request $request, $comment)
    {
        $commentInstance = new Comment();
        $comment = $commentInstance->find($comment);
        if (! $comment) {
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
        $commentInstance = new Comment;
        $finded = $commentInstance->find($comment);
        if (! $finded) {
            return response('', 404);
        }
        $result = $finded->update($data);
        if ($result) {
            return response('', 200);
        }

        return response('', 500);
    }
}
