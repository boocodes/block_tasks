<?php

namespace Final7\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as RootProvider;

use Final7\App\Events\TaskCompletedEvent;
use Final7\App\Events\TaskCreatedEvent;
use Final7\App\Events\TaskStatusChangedEvent;

use Final7\App\Listeners\TaskCompletedListener;
use Final7\App\Listeners\TaskCreatedListener;
use Final7\App\Listeners\TaskStatusChangedListener;

use Final7\App\Listeners\WebhookListener;

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
            TaskCompletedListener::class,
            [WebhookListener::class, 'handleCompletedTask']
        ],
        TaskCreatedEvent::class => [
            TaskCreatedListener::class
        ],
        TaskStatusChangedEvent::class => [
            TaskStatusChangedListener::class,
            [WebhookListener::class, 'handleStatusChanged']
        ],
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
