<?php

namespace Task2\App\Enums;

enum TaskStatus: string
{
    case NEW = "new";
    case IN_PROGRESS = "in_progress";
    case DONE = "done";
}
