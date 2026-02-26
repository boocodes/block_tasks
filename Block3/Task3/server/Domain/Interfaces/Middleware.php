<?php

namespace Task3\Domain\Interfaces;


use Task3\Infrastructure\Request\Request;

interface Middleware
{
    public function run(Request $request): bool;
}