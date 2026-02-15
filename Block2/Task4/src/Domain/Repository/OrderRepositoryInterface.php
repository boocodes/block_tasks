<?php


namespace Domain\Repository;

use Domain\Entity\Order;

interface OrderRepositoryInterface
{
    public function findAll(): array;
    public function save(Order $order): void;
}