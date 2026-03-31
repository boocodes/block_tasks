<?php

namespace Final5\App\Http\Controllers;

use Final5\App\Http\Requests\Comment\CreateRequest;
use Final5\App\Http\Requests\Comment\UpdateRequest;
use Final5\App\Http\Requests\Webhook\CreateWebhookRequest;
use Final5\App\Http\Requests\Webhook\UpdateWebhookRequest;
use Final5\App\Models\Comment;
use Final5\App\Models\User;
use Final5\App\Models\Webhook;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function get(Request $request, $comment)
    {
        $webhookInstance = new Webhook();
        return $this->webhookRepository->get($request, $comment);
    }

    public function getAll(Request $request)
    {
        return $this->webhookRepository->getAll($request);
    }

    public function add(CreateWebhookRequest $request)
    {
        return $this->webhookService->create($request);
    }

    public function update(UpdateWebhookRequest $request, $comment)
    {
        $webhookInstance = new Webhook();
        return $this->webhookService->update($request, $comment);
    }

    public function delete(Request $request, $comment)
    {
        $webhookInstance = new Webhook();
        return $this->webhookService->delete($request, $comment);
    }
}
