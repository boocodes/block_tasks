<?php


namespace Domain\Service;

interface LoggerInterface
{
    public function logMessage(string $message): void;
}