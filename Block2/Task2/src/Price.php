<?php

namespace Task2;


use http\Exception\BadMethodCallException;

class Price
{
    private float $amount;
    private string $currency;

    public function __construct(float $amount, string $currency)
    {
        if ($amount < 0) {
            throw new BadMethodCallException('Amount not valid');
        }
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}