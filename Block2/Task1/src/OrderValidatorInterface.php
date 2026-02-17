<?php

namespace Task1;

interface OrderValidatorInterface
{
    public function getResponseStatus(): bool;
    public function getResponseValue(): string;
    public function validate(array $input): bool;
}