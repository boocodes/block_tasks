<?php


namespace Infrastructure\Service;

use Domain\Service\ClockInterface;


class ServiceClock implements ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}