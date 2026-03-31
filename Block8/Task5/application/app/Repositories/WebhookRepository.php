<?php

namespace Final5\App\Repositories;

use Final5\App\Http\Resources\Project\ProjectResource;
use Final5\App\Models\Project;
use Final5\App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Final5\App\Http\Resources\WebhookResource;
use Final5\App\Models\Webhook;
use Final5\App\Repositories\Interfaces\WebhookRepositoryInterface;

class WebhookRepository implements WebhookRepositoryInterface
{
    public function get(Request $request, $project)
    {
        $webhookInstance = new Webhook();
        $finded = $webhookInstance->find($project);
        if (! $finded) {
            return response('', 404);
        }
        $response = new WebhookResource($finded);
        return $response;
    }

    public function getAll(Request $request)
    {
        $webhookInstance = new Webhook();
        $resultArray = $webhookInstance->query()->orderBy('id')->get();

        return WebhookResource::collection($resultArray);
    }
}
