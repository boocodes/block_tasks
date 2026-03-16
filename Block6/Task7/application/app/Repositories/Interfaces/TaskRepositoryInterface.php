<?php

namespace Task7\App\Repositories\Interfaces;

use Task7\App\Models\Task;
use Task7\App\Http\Requests\Task\GetRequest;
use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function all(GetRequest $request);
    public function getById(Request $request, $task);
}
