<?php


namespace Task4\App\Http\Controllers\Task;

use Task4\App\Models\Task;
use Illuminate\Http\Request;

class DeleteController extends BaseController
{
    public function __invoke(Request $request, $task)
    {
        $this->authorize('delete', new Task()->find($task));
        return $this->service->delete($request, $task);
    }
}
