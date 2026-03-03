<?php

namespace Task6\App\Repositories\Interfaces;

use Task6\App\Models\Task;
use Task6\App\Http\Requests\Task\GetRequest;
use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function all(GetRequest $request);
    public function getById(Request $request, $task);
}
