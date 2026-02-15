<?php

namespace Task4\Domain;

use DateTimeImmutable;

interface ClockInterface
{
    public function nowDate(): DateTimeImmutable;
}