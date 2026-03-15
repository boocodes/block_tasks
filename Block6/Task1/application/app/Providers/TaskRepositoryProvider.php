<?php

namespace Task1\App\Providers;

use Task1\App\Repositories\Interfaces\TaskRepositoryInterface;
use Task1\App\Repositories\TaskRepository;
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
