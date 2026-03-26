<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CrudRepositoryInterface;
use App\Services\CommentService;
use App\Services\ProjectService;
use App\Services\TaskService;

abstract class Controller
{
    protected $taskService;
    protected $projectService;
    protected $commentService;
    protected CrudRepositoryInterface $commentRepository;
    protected CrudRepositoryInterface $projectRepository;
    protected CrudRepositoryInterface $taskRepository;

    public function __construct
    (
        TaskService $taskService,
        ProjectService $projectService,
        CommentService $commentService,
        CrudRepositoryInterface $commentRepository,
        CrudRepositoryInterface $projectRepository,
        CrudRepositoryInterface $taskRepository,
    )
    {
        $this->taskService = $taskService;
        $this->projectService = $projectService;
        $this->commentService = $commentService;
        
        $this->commentRepository = $commentRepository;
        $this->taskRepository = $taskRepository;
        $this->projectRepository = $projectRepository;
    }
}
