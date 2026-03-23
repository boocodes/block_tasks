<?php

namespace Task6\Domain\Interfaces;


use Task6\Infrastructure\Request\Request;

interface Middleware
{
    public function run(Request $request): bool;
}