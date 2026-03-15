<?php

namespace Task3\App\Repositories\Interfaces;

use Task3\App\Models\Task;
use Task3\App\Http\Requests\Task\GetRequest;
use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function all(GetRequest $request);
    public function getById(Request $request, $task);
}
