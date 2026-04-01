<?php

namespace Final7\App\Services;

use Final7\App\Enums\TaskStatus;
use Final7\App\Events\TaskCompletedEvent;
use Final7\App\Events\TaskCreatedEvent;
use Final7\App\Events\TaskStatusChangedEvent;
use Final7\App\Http\Requests\Webhook\CreateWebhookRequest;
use Final7\App\Http\Requests\Webhook\UpdateWebhookRequest;
use Final7\App\Models\Webhook;
use Illuminate\Http\Request;

class WebhookService
{
    public function create(CreateWebhookRequest $request)
    {
        $data = $request->validated();
        $webhookInstance = new Webhook();
        $result = $webhookInstance->create($data);
        if ($result) {
            return response('', 201);
        }
        return response('', 500);
    }

    public function delete(Request $request, $task)
    {
        $webhookInstance = new Webhook();
        $webhookResult = $webhookInstance->find($task);
        if (! $webhookResult) {
            return response('', 404);
        }
        if ($webhookResult->delete()) {
            return response('', 204);
        }
        return response('', 500);
    }

    public function update(UpdateWebhookRequest $request, $task)
    {
        $data = $request->validated();
        $webhookInstance = new Webhook();
        $finded = $webhookInstance->find($task);
        if (! $finded) {
            return response('', 404);
        }
        $result = $finded->update($data);
        if ($result) {
            return response('', 200);
        }
        return response('', 500);
    }
}
