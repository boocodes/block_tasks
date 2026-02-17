<?php

namespace Task5;

use Task1\OrderValidator;

require 'vendor/autoload.php';


$data = [
    'email' => 'test@mail.ru',
    'items' => [
        ['name' => 'Melon', 'price' => 100, 'qty' => 1],
        ['name' => 'Apple', 'price' => 200, 'qty' => 1],
    ],
    'promo' => 'WELCOME'
];

$orderService = new OrderService();


$serviceLocator = new DIContainer();
$serviceLocator->setObject('validator', new OrderValidate());
$serviceLocator->setObject('notifier', new Notifier("admin@mail.ru", $data['email'], true));
$serviceLocator->setObject('file', new OrderFile());

try {
    $result = $orderService->process(
        $data,
        $serviceLocator->get('validator'),
        $serviceLocator->get('notifier'),
        $serviceLocator->get('file')
    );

} catch (\Exception $e) {
    echo $e->getMessage();
}


$customerEmail = "example@mail.ru";
$orderList = new NonanemicOrder([
    ['name' => 'Melon', 'price' => 100, 'qty' => 1],
    ['name' => 'Apple', 'price' => 200, 'qty' => 1],
], $customerEmail);

$orderList->markPaid();

