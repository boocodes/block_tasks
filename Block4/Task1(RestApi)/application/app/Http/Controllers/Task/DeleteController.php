<?php


namespace App\Http\Controllers\Task;


use App\Models\Task;
use Illuminate\Http\Request;

class DeleteController extends BaseController
{
    public function __invoke(Request $request, $task)
    {
        return $this->service->delete($request, $task);
    }
}
