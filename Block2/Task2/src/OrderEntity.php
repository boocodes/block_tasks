<?php


namespace Task2;
class OrderEntity
{
    private array $order;

    public function __construct(OrderId $orderId, Price $orderPrice, array $items)
    {
        $this->order['id'] = $orderId->getId();
        $this->order['pricing']['total'] = $orderPrice->getAmount();
        $this->order['pricing']['currency'] = $orderPrice->getCurrency();
        $this->order['items'] = $items;
    }

    public function getOrder(): array
    {
        return $this->order;
    }

    public function setId(string $id): void
    {
        $this->order['id'] = $id;
    }
    public function setItems(array $items): void
    {
        $this->order['items'] = $items;
    }

    public function setTotal(float $total): void
    {
        $this->order['pricing']['total'] = $total;
    }

}