<?php


use Task4\Application\CreateOrderUseCase;
use Task4\Domain;
use Task4\Application;
use Task4\Infrastructure;
use Task4\Infrastructure\Logger;
use Task4\Infrastructure\OrderRepository;

$simpleInputData = [
    'customer' => ['email' => 'test@mail.ru', 'name' => 'Test'],
    'items' => [
        ['sku' => 'A1', 'title' => 'Item', 'price' => 200.00, 'qty' => 2],
        ['sku' => 'A1', 'title' => 'Item', 'price' => 200.00, 'qty' => 2],
        ['sku' => 'A1', 'title' => 'Item', 'price' => 200.00, 'qty' => 2],
    ],
    'payment' => ['method' => 'card', 'cardNumber' => '123456789101124'],
    'delivery' => ['type' => 'pickup', 'address' => 'test'],
    'promoCode' => [],
];

$orderRepository = new OrderRepository();
$logger = new Logger();

$createOrderUseCase = new CreateOrderUseCase();
$logger->log("start");
$createOrderUseCase->proccess($simpleInputData);