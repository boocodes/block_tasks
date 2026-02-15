<?php


namespace Domain\Entity;
use Domain\ValueObject\OrderElem;

class Order
{
    private OrderElem $order;

    public function __construct(OrderElem $order)
    {
        $this->order = $order;
    }
    public function getId(): string
    {
        return $this->order->getId();
    }
    public function getName(): string
    {
        return $this->order->getName();
    }
    public function getPrice(): float
    {
        return $this->order->getPrice();
    }
}