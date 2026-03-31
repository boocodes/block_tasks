<?php

namespace Final6\App\Providers;

use Final6\App\Repositories\CommentRepository;
use Final6\App\Repositories\Interfaces\CommentRepositoryInterface;
use Final6\App\Repositories\Interfaces\ProjectRepositoryInterface;
use Final6\App\Repositories\Interfaces\TaskRepositoryInterface;
use Final6\App\Repositories\Interfaces\WebhookRepositoryInterface;
use Final6\App\Repositories\ProjectRepository;
use Final6\App\Repositories\TaskRepository;
use Final6\App\Repositories\WebhookRepository;
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
        $this->app->bind(
            WebhookRepositoryInterface::class,
            WebhookRepository::class
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
