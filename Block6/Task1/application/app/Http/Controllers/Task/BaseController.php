<?php

namespace Task4\App\Http\Controllers\Task;

use Task4\App\Http\Controllers\Controller;
use Task4\App\Repositories\Interfaces\TaskRepositoryInterface;
use Task4\App\Repositories\TaskRepository;
use Task4\App\Services\Task\TaskService;
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
