<?php

namespace Task6\App\Providers;

use Task6\App\Repositories\Interfaces\TaskRepositoryInterface;
use Task6\App\Repositories\TaskRepository;
use Illuminate\Support\ServiceProvider;

class TaskRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
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
