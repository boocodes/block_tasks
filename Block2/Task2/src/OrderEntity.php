<?php


namespace Task2;
class OrderEntity
{
    private string $status;
    private string $customerEmail;
    private string $orderId;
    private float $totalAmount;
    private string $currency;
    private array $items;

    public function __construct(OrderId $orderId, Email $email, array $items, string $currency)
    {
        $this->status = 'new';
        $this->customerEmail = $email->getEmail();
        $this->orderId = $orderId->getId();
        $this->totalAmount = 0;
        $this->currency = $currency;
        $this->items = $items;
    }

    public function getOrder(): array
    {
        return [
            'status' => $this->status,
            'customer' => ['email' => $this->customerEmail],
            'orderId' => $this->orderId,
            'pricing' => [
                'totalAmount' => $this->totalAmount,
                'currency' => $this->currency
            ],
            'items' => $this->items
        ];
    }

    public function markPaid(): array
    {
        if($this->status == 'new')
        {
            $this->totalAmount = 0;
            $this->calculateTotal();
            $this->status = 'paid';
        }
        return $this->getOrder();
    }
    public function calculateTotal(): float
    {

        if(empty($this->items)) return 0.0;
        foreach ($this->items as $item) {
            $item['price'] = isset($item['price']) ? (float)$item['price'] : 0.0;
            $this->totalAmount += $item['price'] * $item['qty'];
        }
        unset($item);
        $orderPrice = new Price($this->totalAmount, $this->currency);
        return $this->totalAmount;
    }

}