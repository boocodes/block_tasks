<?php

namespace Final3\App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface ProjectRepositoryInterface
{
    public function get(Request $request, $project);

    public function getAll(Request $request);
}
