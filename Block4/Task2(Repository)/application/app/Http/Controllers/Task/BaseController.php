<?php

namespace Task2\App\Http\Controllers\Task;

use Task2\App\Http\Controllers\Controller;
use Task2\App\Repositories\Interfaces\TaskRepositoryInterface;
use Task2\App\Repositories\TaskRepository;
use Task2\App\Services\Task\TaskService;


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
