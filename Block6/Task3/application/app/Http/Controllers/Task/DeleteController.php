<?php


namespace Task3\App\Http\Controllers\Task;

use Task3\App\Models\Task;
use Illuminate\Http\Request;

class DeleteController extends BaseController
{
    public function __invoke(Request $request, $task)
    {
        $this->authorize('delete', new Task()->find($task));
        return $this->service->delete($request, $task);
    }
}
