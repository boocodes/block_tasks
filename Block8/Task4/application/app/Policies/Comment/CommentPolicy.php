<?php

namespace Final4\App\Policies\Comment;

use Final4\App\Models\User;
use Final4\App\Models\Comment;

class CommentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }
    public function view(User $user, Comment $comment): bool
    {
        return true;
    }
    public function create(User $user): bool
    {
        return true;
    }
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
    public function restore(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
}
