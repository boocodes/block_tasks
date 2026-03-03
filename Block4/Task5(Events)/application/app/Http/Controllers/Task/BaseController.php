<?php

namespace Task5\App\Http\Controllers\Task;

use Task5\App\Http\Controllers\Controller;
use Task5\App\Repositories\Interfaces\TaskRepositoryInterface;
use Task5\App\Repositories\TaskRepository;
use Task5\App\Services\Task\TaskService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseController extends Controller
{
    protected $service;
    protected TaskRepositoryInterface $taskRepository;
    use AuthorizesRequests;

    public function __construct(TaskService $service, TaskRepositoryInterface $taskRepository)
    {
        $this->service = $service;
        $this->taskRepository = $taskRepository;
    }

}
