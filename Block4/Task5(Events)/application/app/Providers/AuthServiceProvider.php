<?php

namespace Task5\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ProviderRoot;

use Task5\App\Models\Task;
use Task5\App\Policies\TaskPolicy;

class AuthServiceProvider extends ProviderRoot
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
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
