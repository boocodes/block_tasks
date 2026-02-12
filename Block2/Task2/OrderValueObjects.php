<?php


class OrderValueObjects
{
    public readonly string $email;
    public readonly string $currency;
    public readonly float $amount;
    public readonly string $orderId;

    /**
     * @throws Exception
     */
    public function __construct(string $email, string $currency, float $amount, string $orderId)
    {
        if(trim($email) === '' || !str_contains($email, '@')) {
            throw new Exception('Email not valid');
        }
        if($amount < 0)
        {
            throw new Exception('Amount not valid');
        }
        else
        {
            $this->email = $email;
            $this->currency = $currency;
            $this->amount = $amount;
            $this->orderId = $orderId;
        }
    }

    /**
     * @throws Exception
     */
    public function add(OrderValueObjects $orderValueObjects): OrderValueObjects
    {
        if($this->currency !== $orderValueObjects->currency) {
            throw new Exception('Currency is not equal to currency');
        }
        else
        {
            return new OrderValueObjects($this->email, $this->currency, $this->amount + $orderValueObjects->amount, $this->orderId);
        }
    }
}