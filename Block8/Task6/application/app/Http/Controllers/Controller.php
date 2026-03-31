<?php

namespace Final6\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Final6\App\Repositories\Interfaces\CommentRepositoryInterface;
use Final6\App\Repositories\Interfaces\ProjectRepositoryInterface;
use Final6\App\Repositories\Interfaces\TaskRepositoryInterface;
use Final6\App\Repositories\Interfaces\WebhookRepositoryInterface;
use Final6\App\Services\CommentService;
use Final6\App\Services\ProjectService;
use Final6\App\Services\TaskService;
use Final6\App\Services\UserService;
use Final6\App\Services\WebhookService;

abstract class Controller
{
    use AuthorizesRequests;

    protected $taskService;

    protected $webhookService;

    protected $userService;

    protected $projectService;

    protected $commentService;

    protected CommentRepositoryInterface $commentRepository;

    protected ProjectRepositoryInterface $projectRepository;

    protected TaskRepositoryInterface $taskRepository;

    protected WebhookRepositoryInterface $webhookRepository;

    public function __construct(
        TaskService $taskService,
        ProjectService $projectService,
        CommentService $commentService,
        UserService $userService,
        WebhookService $webhookService,
        CommentRepositoryInterface $commentRepository,
        ProjectRepositoryInterface $projectRepository,
        TaskRepositoryInterface $taskRepository,
        WebhookRepositoryInterface $webhookRepository,
    ) {
        $this->taskService = $taskService;
        $this->projectService = $projectService;
        $this->commentService = $commentService;
        $this->userService = $userService;
        $this->webhookService = $webhookService;

        $this->commentRepository = $commentRepository;
        $this->taskRepository = $taskRepository;
        $this->projectRepository = $projectRepository;
        $this->webhookRepository = $webhookRepository;
    }
}
