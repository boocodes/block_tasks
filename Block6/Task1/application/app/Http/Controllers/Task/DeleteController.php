<?php


namespace Task1\App\Http\Controllers\Task;

use Task1\App\Models\Task;
use Illuminate\Http\Request;

class DeleteController extends BaseController
{
    public function __invoke(Request $request, $task)
    {
        $this->authorize('delete', new Task()->find($task));
        return $this->service->delete($request, $task);
    }
}
