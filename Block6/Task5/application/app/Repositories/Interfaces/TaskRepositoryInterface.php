<?php

namespace Task5\App\Repositories\Interfaces;

use Task5\App\Models\Task;
use Task5\App\Http\Requests\Task\GetRequest;
use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function all(GetRequest $request);
    public function getById(Request $request, $task);
}
