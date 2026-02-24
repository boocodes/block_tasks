<?php


namespace Task3\Domain\Enums;
enum StatusEnum: string
{
    case New = "new";
    case InProgress = "in_progress";
    case Done = "done";
}