<?php

namespace Task6\App\Events;

use Task6\App\Enums\TaskStatus;
use Task6\App\Http\Requests\Task\CreateRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Task6\App\Models\TaskAudit;
use Task6\App\Models\Task;

class TaskCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public TaskStatus $previousStatus;
    public Task $task;
    public function __construct(Task $task, TaskStatus $previousStatus)
    {
        $this->task = $task;
        $this->previousStatus = $previousStatus;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
