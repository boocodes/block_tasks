<?php

namespace Task6\App\Http\Controllers\Task;

use Task6\App\Http\Controllers\Controller;
use Task6\App\Repositories\Interfaces\TaskRepositoryInterface;
use Task6\App\Repositories\TaskRepository;
use Task6\App\Services\Task\TaskService;
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
