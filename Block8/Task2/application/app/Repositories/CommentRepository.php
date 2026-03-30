<?php

namespace Final2\App\Repositories;

use Final2\App\Http\Resources\Comment\CommentResource;
use Final2\App\Models\Comment;
use Final2\App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Http\Request;

class CommentRepository implements CommentRepositoryInterface
{
    public function get(Request $request, $comment)
    {
        $commentInstance = new Comment();
        $resultComment = $commentInstance->find($comment);
        if (! $resultComment) {
            return response('', 404);
        }
        $response = new CommentResource($resultComment);

        return $response;
    }

    public function getAll(Request $request)
    {
        $comment = new Comment();
        $resultArray = $comment->query()->orderBy('id')->get();

        return CommentResource::collection($resultArray);
    }
}
