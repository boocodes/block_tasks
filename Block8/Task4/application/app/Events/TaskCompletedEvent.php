<?php

namespace Final4\App\Events;

use Final4\App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Task $task;
    public $userId;
    public string $idempotencyKey;

    /**
     * Create a new event instance.
     */
    public function __construct(Task $task, $userId, ?string $idempotencyKey)
    {
        $this->task = $task;
        $this->userId = $userId;
        if (!$idempotencyKey) {
            $this->idempotencyKey = 'task_' . $userId . '_' . new \DateTimeImmutable()->format('c');
        } else {
            $this->idempotencyKey = $idempotencyKey;
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
