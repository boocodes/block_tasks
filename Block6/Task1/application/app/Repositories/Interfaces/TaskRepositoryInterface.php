<?php

namespace Task4\App\Repositories\Interfaces;

use Task4\App\Models\Task;
use Task4\App\Http\Requests\Task\GetRequest;
use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function all(GetRequest $request);
    public function getById(Request $request, $task);
}
