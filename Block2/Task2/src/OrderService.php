<?php

namespace Task2;




class OrderService
{
    private Validator $validator;
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function createOrder(OrderRequest $orderRequest): OrderEntity|bool
    {
        $orderTotal = 0;
        if (!$this->validator->validate($orderRequest)) return false;
        try {
            foreach ($orderRequest->items as $item) {
                $item['price'] = isset($item['price']) ? (float)$item['price'] : 0.0;
                $orderTotal += $item['price'] * $item['qty'];
            }
            unset($item);

            $orderEntity = new OrderEntity(
                new OrderId(),
                new Price($orderTotal, $orderRequest->payment['currency']),
                new Email($orderRequest->customer['email']),
                $orderRequest->items
            );
            return $orderEntity;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}

