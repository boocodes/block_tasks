<?php

require_once './OrderRequest.php';
require_once './OrderService.php';


$data = [
    'customer' => ['email' => 'example@mail.ru', 'name' => 'test'],
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
$orderService = new OrderService();
$orderRequest = new OrderRequest($data);

$orderService->createOrder($orderRequest);
