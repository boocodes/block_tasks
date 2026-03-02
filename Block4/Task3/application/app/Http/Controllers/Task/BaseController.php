<?php

namespace Task3\App\Http\Controllers\Task;

use Task3\App\Http\Controllers\Controller;
use Task3\App\Repositories\Interfaces\TaskRepositoryInterface;
use Task3\App\Repositories\TaskRepository;
use Task3\App\Services\Task\TaskService;


class BaseController extends Controller
{
    protected $service;
    protected TaskRepositoryInterface $taskRepository;

    public function __construct(TaskService $service, TaskRepositoryInterface $taskRepository)
    {
        $this->service = $service;
        $this->taskRepository = $taskRepository;
    }

}
