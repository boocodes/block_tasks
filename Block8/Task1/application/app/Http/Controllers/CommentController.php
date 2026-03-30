<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CreateRequest;
use App\Http\Requests\Comment\UpdateRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function get(Request $request, $comment)
    {
        return $this->commentRepository->get($request, $comment);
    }

    public function getAll(Request $request)
    {
        return $this->commentRepository->getAll($request);
    }

    public function add(CreateRequest $request)
    {
        return $this->commentService->create($request);
    }

    public function update(UpdateRequest $request, $comment)
    {
        return $this->commentService->update($request, $comment);
    }

    public function delete(Request $request, $comment)
    {
        return $this->commentService->delete($request, $comment);
    }
}
