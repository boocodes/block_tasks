<?php

namespace Final7\App\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as RootProvider;
use Final7\App\Models\Task;
use Final7\App\Models\Comment;
use Final7\App\Models\Project;


use Final7\App\Policies\Task\TaskPolicy;
use Final7\App\Policies\Comment\CommentPolicy;
use Final7\App\Policies\Project\ProjectPolicy;



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
