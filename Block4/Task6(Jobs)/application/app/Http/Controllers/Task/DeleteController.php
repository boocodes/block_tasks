<?php


namespace Task6\App\Http\Controllers\Task;

use Illuminate\Http\Request;
use Task6\App\Models\Task;

class DeleteController extends BaseController
{
    public function __invoke(Request $request, $task)
    {
        $this->authorize('delete', new Task()->find($task));
        return $this->service->delete($request, $task);
    }
}
