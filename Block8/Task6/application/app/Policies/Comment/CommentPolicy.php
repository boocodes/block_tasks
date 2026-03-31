<?php

namespace Final6\App\Policies\Comment;

use Final6\App\Models\User;
use Final6\App\Models\Comment;
use Final6\App\Models\Project;
use Final6\App\Models\Task;

class CommentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }
    public function view(User $user, Comment $comment): bool
    {
        $task = Task::find($comment->task_id);
        if(!$task)
            {
                return false;
            }
        $project = Project::find($task->project_id);
        if(!$project)
            {
                return false;
            }
        return $project->owner_id === $user->id;
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
