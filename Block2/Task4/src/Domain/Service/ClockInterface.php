<?php


namespace Domain\Service;

use DateTimeImmutable;

interface ClockInterface
{
    public function now(): DateTimeImmutable;
}