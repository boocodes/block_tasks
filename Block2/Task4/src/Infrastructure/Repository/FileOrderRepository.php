<?php

namespace Infrastructure\Repository;

use Domain\Entity\Order;
use Domain\Repository\OrderRepositoryInterface;
use Domain\ValueObject\OrderElem;

class FileOrderRepository implements OrderRepositoryInterface
{
    private array $orders = [];

    public function findAll(): array
    {
        return $this->orders;
    }

    public function save(Order $order): void
    {
        $this->orders[] = $order;
    }
}