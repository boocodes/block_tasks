<?php

namespace Final7\App\Repositories;

use Final7\App\Http\Resources\Comment\CommentResource;
use Final7\App\Models\Comment;
use Final7\App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Http\Request;

class CommentRepository implements CommentRepositoryInterface
{
    public function get(Request $request, $comment)
    {
        $commentInstance = new Comment();
        $resultComment = $commentInstance->with(['user', 'task'])->find($comment);

        if(!$resultComment)
            {
                return response('', 404);
            }
        $response = new CommentResource($resultComment);
        return $response;
    }

    public function getAll(Request $request)
    {
        $comments = Comment::query()
            ->with(['user', 'task'])
            ->orderBy('id')
            ->get();
        return CommentResource::collection($comments);
    }
}
