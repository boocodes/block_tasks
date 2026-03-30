<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Services\CommentService;
use App\Services\ProjectService;
use App\Services\TaskService;

abstract class Controller
{
    protected $taskService;

    protected $projectService;

    protected $commentService;

    protected CommentRepositoryInterface $commentRepository;

    protected ProjectRepositoryInterface $projectRepository;

    protected TaskRepositoryInterface $taskRepository;

    public function __construct(
        TaskService $taskService,
        ProjectService $projectService,
        CommentService $commentService,
        CommentRepositoryInterface $commentRepository,
        ProjectRepositoryInterface $projectRepository,
        TaskRepositoryInterface $taskRepository,
    ) {
        $this->taskService = $taskService;
        $this->projectService = $projectService;
        $this->commentService = $commentService;

        $this->commentRepository = $commentRepository;
        $this->taskRepository = $taskRepository;
        $this->projectRepository = $projectRepository;
    }
}
