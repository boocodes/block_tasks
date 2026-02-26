<?php

namespace Task4\Domain\Interfaces;


use Task4\Infrastructure\Request\Request;

interface Middleware
{
    public function run(Request $request): bool;
}