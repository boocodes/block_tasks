<?php

namespace Final3\App\Providers;

use Final3\App\Repositories\CommentRepository;
use Final3\App\Repositories\Interfaces\CommentRepositoryInterface;
use Final3\App\Repositories\Interfaces\ProjectRepositoryInterface;
use Final3\App\Repositories\Interfaces\TaskRepositoryInterface;
use Final3\App\Repositories\ProjectRepository;
use Final3\App\Repositories\TaskRepository;
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
