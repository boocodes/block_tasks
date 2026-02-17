<?php

namespace Task2;




class OrderService
{
    private Validator $validator;
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function createOrder(OrderRequest $orderRequest): array|bool
    {
        if (!$this->validator->validate($orderRequest)) return false;
        try {
            $orderEntity = new OrderEntity(
                new OrderId(),
                new Email($orderRequest->customer['email']),
                $orderRequest->items,
                $orderRequest->payment['currency'],
            );
            return $orderEntity->markPaid();
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}

