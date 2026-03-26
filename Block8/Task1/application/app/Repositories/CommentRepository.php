<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Resources\Comment\CommentResource;
use App\Repositories\Interfaces\CrudRepositoryInterface;

class CommentRepository extends CrudRepositoryInterface
{
    public function get(Request $request, $id)
    {
        $result = new Comment();
        $result->find($id);
        if (!$result) {
            return response('', 404);
        }
        $response = new CommentResource($result);
        return $response;
    }
    public function getAll(Request $request)
    {
        $comment = new Comment();
        $resultArray = $comment->query()->orderBy('id')->get();
        return CommentResource::collection($resultArray);
    }
}
