<?php

namespace Final2\App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface CommentRepositoryInterface
{
    public function get(Request $request, $comment);

    public function getAll(Request $request);
}
