<?php

namespace Task2\App\Repositories\Interfaces;

use Task2\App\Models\Task;
use Task2\App\Http\Requests\Task\GetRequest;

interface TaskRepositoryInterface
{
    public function all(GetRequest $request);
    public function getById( $task);
}
