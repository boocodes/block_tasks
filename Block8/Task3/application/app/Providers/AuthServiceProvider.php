<?php

namespace Final3\App\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as RootProvider;
use Final3\App\Models\Task;
use Final3\App\Models\Comment;
use Final3\App\Models\Project;


use Final3\App\Policies\Task\TaskPolicy;
use Final3\App\Policies\Comment\CommentPolicy;
use Final3\App\Policies\Project\ProjectPolicy;



class AuthServiceProvider extends RootProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    protected $policies = [
        Task::class => TaskPolicy::class,
        Comment::class => CommentPolicy::class,
        Project::class => ProjectPolicy::class,
    ];

    /** 
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
