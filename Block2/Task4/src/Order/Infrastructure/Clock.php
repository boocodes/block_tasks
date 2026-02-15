<?php

namespace Task4\Infrastructure;

use DateTimeImmutable;
use Task4\Domain\ClockInterface;

class Clock implements ClockInterface {

    public function nowDate(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}