<?php

namespace Task2;




class OrderService
{
    private Validator $validator;
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function createOrder(OrderRequest $orderRequest, Email $orderEmail, Price $orderPrice, OrderId $orderId): OrderEntity|bool
    {
        if (!$this->validator->validate($orderRequest)) return false;
        try {
            foreach ($orderRequest->items as $item) {
                $item['price'] = isset($item['price']) ? (float)$item['price'] : 0.0;
                $orderPrice->setAmount($orderPrice->getAmount() + $item['price']);
            }
            unset($item);

            $orderEntity = new OrderEntity($orderId, $orderPrice, $orderRequest->items);
            var_dump($orderEntity->getOrder());
            return $orderEntity;
        } catch (\BadMethodCallException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}

