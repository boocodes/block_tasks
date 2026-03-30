<?php

namespace Final3\App\Enums;

enum Priority: string
{
    case LOW = 'low';
    case NORMAL = 'normal';
    case HIGH = 'high';
    case CRITICAL = 'critical';
}
