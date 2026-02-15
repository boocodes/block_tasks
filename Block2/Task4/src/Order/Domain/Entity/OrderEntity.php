<?php

namespace Task4\Domain\Entity;


class OrderEntity
{


    public function __construct(
        private string $id,
        private string $createdAt,
        private array  $customer,
        private array  $items,
        private array  $delivery,
        private array  $payment,
        private array  $pricing,
    )
    {
    }

    private function calculateTotalPrice(): float
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item['price'];
        }
        if($total < 0 ) return 0.0;
        else return $total;
    }

}
