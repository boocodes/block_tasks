<?php

namespace Final2\App\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as RootProvider;
use Final2\App\Models\Task;
use Final2\App\Models\Comment;
use Final2\App\Models\Project;


use Final2\App\Policies\Task\TaskPolicy;
use Final2\App\Policies\Comment\CommentPolicy;
use Final2\App\Policies\Project\ProjectPolicy;



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
