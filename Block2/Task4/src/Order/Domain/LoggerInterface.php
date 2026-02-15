<?php

namespace Task4\Domain;

interface LoggerInterface
{
    public function log(string $message): void;
}