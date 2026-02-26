<?php

namespace Task2\Domain\Interfaces;


use Task2\Infrastructure\Request\Request;

interface Middleware
{
    public function run(Request $request): bool;
}