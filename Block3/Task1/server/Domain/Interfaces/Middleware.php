<?php

namespace Task1\Domain\Interfaces;


use Task1\Infrastructure\Request\Request;

interface Middleware
{
    public function run(Request $request): bool;
}