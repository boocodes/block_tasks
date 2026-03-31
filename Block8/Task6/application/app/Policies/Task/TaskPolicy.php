<?php

namespace Final6\App\Policies\Task;

use Final6\App\Models\Project;
use Final6\App\Models\User;
use Final6\App\Models\Task;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }
    public function view(User $user, Task $task): bool
    {
        return true;
    }
    public function create(User $user): bool
    {
        return true;
    }
    public function update(User $user, Task $task): bool
    {
        $project = new Project();
        $findedProject = $project->find($task->project_id);
        
        return $findedProject->owner_id === $user->id;
    }
    public function delete(User $user, Task $task): bool
    {
        $project = new Project();
        $findedProject = $project->find($task->project_id);
        
        return $findedProject->owner_id === $user->id;
    }
    public function restore(User $user, Task $task): bool
    {
        $project = new Project();
        $findedProject = $project->find($task->project_id);
        
        return $findedProject->owner_id === $user->id;
    }
    public function forceDelete(User $user, Task $task): bool
    {
        $project = new Project();
        $findedProject = $project->find($task->project_id);
        
        return $findedProject->owner_id === $user->id;
    }
}
