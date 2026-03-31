<?php

namespace Final7\App\Http\Controllers;

use Final7\App\Http\Requests\Comment\CreateRequest;
use Final7\App\Http\Requests\Comment\UpdateRequest;
use Final7\App\Models\Comment;
use Final7\App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function get(Request $request, $comment)
    {
        $commentInstance = new Comment();
        $this->authorize('view', $commentInstance->find($comment));
        return $this->commentRepository->get($request, $comment);
    }

    public function getAll(Request $request)
    {
        $this->authorize('viewAny', [Comment::class, User::class]);
        return $this->commentRepository->getAll($request);
    }

    public function add(CreateRequest $request)
    {
        $this->authorize('create', [Comment::class, User::class]);
        return $this->commentService->create($request);
    }

    public function update(UpdateRequest $request, $comment)
    {
        $commentInstance = new Comment();
        $this->authorize('update', $commentInstance->find($comment));
        return $this->commentService->update($request, $comment);
    }

    public function delete(Request $request, $comment)
    {
        $commentInstance = new Comment();
        $this->authorize('delete', $commentInstance->find($comment));
        return $this->commentService->delete($request, $comment);
    }
}
