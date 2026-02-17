<?php

namespace Task5;

class NonanemicOrder
{
    private string $id;
    private Notifier $notifier;
    private OrderFile $orderFile;
    private string $customerEmail;
    private array $items = [];
    private float $subtotal = 0.0;
    private float $discount = 0.0;
    private float $tax = 0.0;
    private float $total = 0.0;
    private string $status;
    private string $createdAt;

    public function __construct(array $items, string $customerEmail)
    {
        $this->items = $items;
        $this->id = uniqid();
        $this->customerEmail = $customerEmail;
        $this->status = 'new';
        $this->createdAt = date('c');
    }

    public function addItem(array $item): void
    {
        $this->items[] = $item;
    }

    public function getItem(int $index): ?array
    {
        return $this->items[$index] ?? null;
    }
    public function setCustomerEmail(string $customerEmail): void
    {
        $this->customerEmail = $customerEmail;
    }

    public function removeItem(int $index): void
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
        }
    }
    public function setTax(float $tax): void
    {
        $this->tax = $tax;
    }
    public function setDiscount(float $discount): void
    {
        $this->discount = $discount;
    }
    public function markPaid(): void
    {
        $this->calculcateSubtotal();
        $this->calculateTotal();
        $this->status = 'paid';
        $this->save();
    }
    private function calculcateSubtotal(): void
    {
        $this->subtotal = 0.0;
        foreach ($this->items as $item) {
            $this->subtotal += $item['price'] * $item['qty'] ?? 1;
        }
        unset($item);
    }
    private function calculateTotal(): void
    {
        if($this->discount >= 0.0 && $this->discount <= $this->subtotal){
            $this->total = $this->subtotal - $this->discount;
            $this->total += ($this->total * $this->tax);
        }
    }
    public function markCanceled(): void
    {
        if($this->status == "paid")
        {
            $this->status = 'canceled';
        }
    }
    public function save(): void
    {
        if($this->status == "paid")
        {
            $result = [
                'id' => $this->id,
                'customerEmail' => $this->customerEmail,
                'items' => $this->items,
                'subtotal' => $this->subtotal,
                'discount' => $this->discount,
                'tax' => $this->tax,
                'total' => $this->total,
                'status' => $this->status,
                'createdAt' => $this->createdAt,
            ];
        }
    }
}
