<?php

namespace Final2\App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function get(Request $request, $task);

    public function getAll(Request $request);
}
