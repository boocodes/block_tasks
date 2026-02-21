<?php

namespace Task7\Domain\Enums;

enum TaskStatus: string
{
    case NEW = 'new';
    case InProgress = 'in_progress';
    case Done = 'done';
}