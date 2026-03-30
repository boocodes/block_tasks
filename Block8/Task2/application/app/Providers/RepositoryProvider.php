<?php

namespace Final2\App\Providers;

use Final2\App\Repositories\CommentRepository;
use Final2\App\Repositories\Interfaces\CommentRepositoryInterface;
use Final2\App\Repositories\Interfaces\ProjectRepositoryInterface;
use Final2\App\Repositories\Interfaces\TaskRepositoryInterface;
use Final2\App\Repositories\ProjectRepository;
use Final2\App\Repositories\TaskRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            CommentRepositoryInterface::class,
            CommentRepository::class
        );
        $this->app->bind(
            ProjectRepositoryInterface::class,
            ProjectRepository::class
        );
        $this->app->bind(
            TaskRepositoryInterface::class,
            TaskRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
