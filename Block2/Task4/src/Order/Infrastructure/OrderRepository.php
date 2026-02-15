<?php

namespace Task4\Infrastructure;

use Task4\Domain\Entity\OrderEntity;
use Task4\Domain\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{

    private OrderEntity $order;
    public function getOrder(): OrderEntity
    {
        return $this->order;
    }
    public function setOrder(OrderEntity $order)
    {
        $this->order = $order;
    }
}