<?php

namespace Task1\App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ProviderRoot;
use Task1\App\Policies\TaskPolicy;
use Task1\App\Models\Task;


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
