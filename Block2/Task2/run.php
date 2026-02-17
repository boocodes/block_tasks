<?php
namespace Task2;
require_once 'vendor/autoload.php';


$data = [
    'customer' => ['email' => '', 'name' => 'test'],
    'items' => [
        ['sku' => 'A1', 'title' => 'Carrot', 'price' => 200.00, 'qty' => 1],
        ['sku' => 'A1', 'title' => 'Potato', 'price' => 200.00, 'qty' => 1],
        ['sku' => 'A1', 'title' => 'Melon', 'price' => 1000.00, 'qty' => 1],
        ['sku' => 'A1', 'title' => 'Apple', 'price' => 150.00, 'qty' => 1],
    ],
    'payment' => ['method' => 'cash', 'cardNumber' => '', 'currency' => 'rub'],
    'delivery' => ['type' => 'pickup', 'address' => ''],
    'promoCode' => '',
];

$orderService = new OrderService(new \Task2\Validator());

$orderRequest = new OrderRequest($data);


try {
    $orderService->createOrder(
        $orderRequest,
        new \Task2\Email($data['customer']['email']),
        new \Task2\Price(0, $data['payment']['currency']),
        new \Task2\OrderId()
    );
}
catch (\Exception $e) {
    echo $e->getMessage();
}

