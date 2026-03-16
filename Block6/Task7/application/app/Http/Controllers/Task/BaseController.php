<?php

namespace Task7\App\Http\Controllers\Task;

use Task7\App\Http\Controllers\Controller;
use Task7\App\Repositories\Interfaces\TaskRepositoryInterface;
use Task7\App\Repositories\TaskRepository;
use Task7\App\Services\Task\TaskService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class BaseController extends Controller
{
    use AuthorizesRequests;
    protected $service;
    protected TaskRepositoryInterface $taskRepository;

    public function __construct(TaskService $service, TaskRepositoryInterface $taskRepository)
    {
        $this->service = $service;
        $this->taskRepository = $taskRepository;
    }

}
