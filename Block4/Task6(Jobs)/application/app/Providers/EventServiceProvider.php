<?php

namespace Task6\App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as RootProvider;
use Task6\App\Events\TaskCompletedEvent;
use Task6\App\Listeners\WriteTaskAuditLogListener;

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
