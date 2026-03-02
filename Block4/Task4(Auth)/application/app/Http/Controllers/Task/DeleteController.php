<?php


namespace Task4\App\Http\Controllers\Task;

use Illuminate\Http\Request;

class DeleteController extends BaseController
{
    public function __invoke(Request $request, $task)
    {
        return $this->service->delete($request, $task);
    }
}
