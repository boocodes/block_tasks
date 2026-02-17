<?php


namespace Task3;


use DateTimeImmutable;

interface LoggerInterface
{
    public function logMessage(string $message): void;
}

interface OrderRepositoryInterface
{
    public function getOrder(): array;
}

interface ClockInterface
{
    public function nowDate(): DateTimeImmutable;
}


