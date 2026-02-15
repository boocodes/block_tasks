<?php


namespace Infrastructure\Service;

use Domain\Service\LoggerInterface;

class ConsoleLoggerService implements LoggerInterface
{
    public function logMessage(string $message): void
    {
        echo $message . PHP_EOL;
    }
}