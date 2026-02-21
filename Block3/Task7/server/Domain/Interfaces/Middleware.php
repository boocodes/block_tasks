<?php

namespace Task7\Domain\Interfaces;


use Task7\Infrastructure\Request\Request;

interface Middleware
{
    public function run(Request $request): bool;
}