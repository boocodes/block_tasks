<?php

namespace Final5\App\Policies\Project;

use Final5\App\Models\User;
use Final5\App\Models\Project;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }
    public function view(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }
    public function create(User $user): bool
    {
        return true;
    }
    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id;
    }
    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id;
    }
    public function restore(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id;
    }
    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
}
