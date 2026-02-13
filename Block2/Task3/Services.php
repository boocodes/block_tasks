<?php

require_once './OrderInterfaces.php';

class ClockService implements ClockInterface
{
    public function nowDate(): DateTimeImmutable
    {
        return (new DateTimeImmutable());
    }
}

class LoggerService implements LoggerInterface
{
    public function logMessage(string $message): void
    {
        echo $message . "\n";
    }
}

class OrderRepositoryService implements OrderRepositoryInterface
{
    private array $order = [];
    public function __construct()
    {
    }

    public function getOrder(): array
    {
        return $this->order;
    }
    public function setOrder(array $order): void
    {
        $this->order = $order;
    }
}