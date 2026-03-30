<?php

namespace Final3\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Final3\App\Repositories\Interfaces\CommentRepositoryInterface;
use Final3\App\Repositories\Interfaces\ProjectRepositoryInterface;
use Final3\App\Repositories\Interfaces\TaskRepositoryInterface;
use Final3\App\Services\CommentService;
use Final3\App\Services\ProjectService;
use Final3\App\Services\TaskService;
use Final3\App\Services\UserService;

abstract class Controller
{
    use AuthorizesRequests;


    protected $taskService;
    
    protected $userService;

    protected $projectService;

    protected $commentService;

    protected CommentRepositoryInterface $commentRepository;

    protected ProjectRepositoryInterface $projectRepository;

    protected TaskRepositoryInterface $taskRepository;

    public function __construct(
        TaskService $taskService,
        ProjectService $projectService,
        CommentService $commentService,
        UserService $userService,
        CommentRepositoryInterface $commentRepository,
        ProjectRepositoryInterface $projectRepository,
        TaskRepositoryInterface $taskRepository,
    ) {
        $this->taskService = $taskService;
        $this->projectService = $projectService;
        $this->commentService = $commentService;
        $this->userService = $userService;

        $this->commentRepository = $commentRepository;
        $this->taskRepository = $taskRepository;
        $this->projectRepository = $projectRepository;
    }
}
