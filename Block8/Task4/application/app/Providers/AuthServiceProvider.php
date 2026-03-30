<?php

namespace Final4\App\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as RootProvider;
use Final4\App\Models\Task;
use Final4\App\Models\Comment;
use Final4\App\Models\Project;


use Final4\App\Policies\Task\TaskPolicy;
use Final4\App\Policies\Comment\CommentPolicy;
use Final4\App\Policies\Project\ProjectPolicy;



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
