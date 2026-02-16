<?php


namespace Task2\Domain\Enums;
enum StatusEnum: string
{
    case New = "new";
    case InProgress = "in_progress";
    case Done = "done";
}