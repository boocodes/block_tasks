<?php


namespace Infrastructure\Service;

use Domain\Service\ClockInterface;
use DateTimeImmutable;

class ServiceClock implements ClockInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}