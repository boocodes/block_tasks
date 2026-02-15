<?php

namespace Task4\Infrastructure;

use Task4\Domain\LoggerInterface;

class Logger implements LoggerInterface
{

    public function log(string $message): void
    {
        echo $message . "\n";
    }
}