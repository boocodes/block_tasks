<?php

namespace Final2\App\Http\Controllers;

use Final2\App\Http\Requests\Comment\CreateRequest;
use Final2\App\Http\Requests\Comment\UpdateRequest;
use Final2\App\Models\Comment;
use Final2\App\Models\User;
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
