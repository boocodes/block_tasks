<?php

namespace Task5\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as RootProvider;
use Task5\App\Events\TaskCompletedEvent;
use Task5\App\Listeners\WriteTaskAuditLogListener;

class EventServiceProvider extends RootProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }
    protected $listen = [
        TaskCompletedEvent::class => [
            WriteTaskAuditLogListener::class,
        ]
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
