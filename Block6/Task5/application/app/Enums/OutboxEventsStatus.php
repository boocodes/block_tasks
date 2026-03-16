<?php 

namespace Task5\App\Enums;


enum OutboxEventsStatus: string
{
    case NEW = "new";
    case PUBLISHED = "published";
    case FAILED = "failed";
}