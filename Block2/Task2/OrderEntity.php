<?php


class OrderEntity
{
    private array $order;
    public function __construct()
    {
    }

    public function markPaid(): void
    {
        $this->order['status'] = 'paid';
        $now = new \DateTimeImmutable();
        $this->order['date'] = $now->format('c');
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