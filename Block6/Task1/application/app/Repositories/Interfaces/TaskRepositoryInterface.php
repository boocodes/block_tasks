<?php

namespace Task1\App\Repositories\Interfaces;

use Task1\App\Models\Task;
use Task1\App\Http\Requests\Task\GetRequest;
use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function all(GetRequest $request);
    public function getById(Request $request, $task);
}
