<?php

require_once './OrderEntity.php';
require_once './OrderService.php';
require_once './OrderRequest.php';
require_once './OrderValueObjects.php';


class OrderService
{
    public function __construct()
    {
    }

    public function createOrder(OrderRequest $orderRequest): OrderEntity|bool
    {
        if (!$this->validateInput($orderRequest)) return false;

        $orderId = (string)time() . '-' . rand(1000, 9999);
        try {
            $orderValuesObject = new OrderValueObjects($orderRequest->customer['email'],
                $orderRequest->payment['currency'],
                0,
                $orderId
            );

            foreach ($orderRequest->items as $item) {
                $item['price'] = isset($item['price']) ? (float)$item['price'] : 0.0;
                $orderValuesObject = $orderValuesObject->add(new OrderValueObjects($orderRequest->customer['email'],
                        $orderRequest->payment['currency'],
                        $item['price'],
                        $orderId)
                );
            }
            unset($item);

            $orderEntity = new OrderEntity();
            $orderEntity->setId($orderId);
            $orderEntity->setTotal($orderValuesObject->amount);
            $orderEntity->setItems($orderRequest->items);
            $orderEntity->markPaid();
            var_dump($orderEntity->getOrder());
            return $orderEntity;
        } catch (Exception $e) {
            return false;
        }
    }

    private function validateInput(&$input): bool
    {
        if (!isset($input->payment['currency'])) {
            return false;
        }
        if (!isset($input->customer['email'])) {
            return false;
        };
        $email = trim((string)$input->customer['email']);
        if ($email === '' || !str_contains($email, '@')) {
            return false;
        };
        if (!isset($input->items) || !is_array($input->items) || count($input->items) === 0) {
            return false;
        };
        $card = isset($input->payment['cardNumber']) ? preg_replace('/\s+/', '', (string)$input->payment['cardNumber']) : '';
        if (strlen($card) < 12 && $input->payment['method'] === 'card') {
            return false;
        }
        return true;
    }
}

