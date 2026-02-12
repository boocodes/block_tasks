<?php

use Legacy\OrderService;

require_once 'OrderService.php';

$order = new OrderService();

$inputOrderData = [
    'customer' => ['email' => 'example@mail.ru', 'name' => 'test'],
    'items' => [
        ['sku' => 'A1', 'title' => 'Carrot', 'price' => 200.00, 'qty' => 1],
        ['sku' => 'A1', 'title' => 'Potato', 'price' => 200.00, 'qty' => 1],
        ['sku' => 'A1', 'title' => 'Melon', 'price' => 1000.00, 'qty' => 1],
        ['sku' => 'A1', 'title' => 'Apple', 'price' => 150.00, 'qty' => 1],
    ],
    'payment' => ['method' => 'cash', 'cardNumber' => ''],
    'delivery' => ['type' => 'pickup', 'address' => ''],
    'promoCode' => '',
];

var_dump($order->createOrder($inputOrderData));