<?php

namespace Final7\App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface WebhookRepositoryInterface
{
    public function get(Request $request, $task);

    public function getAll(Request $request);
}
