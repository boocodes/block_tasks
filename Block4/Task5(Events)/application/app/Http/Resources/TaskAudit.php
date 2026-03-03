<?php

namespace Task5\App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskAudit extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'task_id' => $this->taskId,
            'event' => 'completed',
            'occurred_at' => $this->occurredAt,
            'meta' => [
                'author_id' => $this->authorId,
                'previous_status' => $this->previousStatus
            ]
        ];
    }
}
