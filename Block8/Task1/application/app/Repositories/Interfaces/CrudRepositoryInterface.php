<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface CrudRepositoryInterface 
{
    public function get(Request $request, $id);
    public function getAll(Request $request);
}