<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Resources\Comment\CommentResource;
use App\Repositories\Interfaces\CrudRepositoryInterface;

class CommentRepository implements CrudRepositoryInterface
{
    public function get(Request $request, $id)
    {
        $commentInstance = new Comment();
        $resultComment = $commentInstance->find($id);
        if (!$resultComment) {
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
