<?php


namespace Task4\Application;


use Task4\Domain\Entity\OrderEntity;

class PayOrderUseCase
{
    public function __construct()
    {
    }

    public function proccess(OrderEntity $order): bool
    {
        return $this->execPayment($order);
    }

    private function execPayment(OrderEntity $order): bool
    {
        return true;
    }
}