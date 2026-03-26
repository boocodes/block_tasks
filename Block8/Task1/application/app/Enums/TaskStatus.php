<?php 

namespace App\Enums;

enum TaskStatus: string
{
    case NEW = "new";
    case IN_PROGRESS = "in_progress";
    case BLOCKED = "blocked";
    case DONE = "done";
    case CANCELLED = "cancelled"; 
}