<?php
namespace Task6;
require 'vendor/autoload.php';


$orderService = new OrderService();

$input = [
    'customer' => [
        'email' => 'test@mail.ru',
        'name' => 'test',
    ],
    'items' => [
        ['sku' => 'A1', 'price' => 100, 'title' => 'melon', 'qty' => 1],
        ['sku' => 'A1', 'price' => 200, 'title' => 'apple', 'qty' => 1],
        ['sku' => 'A1', 'price' => 200, 'title' => 'rice', 'qty' => 1],
    ],
    'delivery' => [
        'type' => 'pickup',
        'address' => 'test',
    ],
    'payment' => [
        'method' => 'cash'
    ],
    'promoCode' => 'good',
];


$promoCodeContainer = new PromoCodeContainer();
$promoCodeContainer->setPromoCode('good', function (&$order)
{
    $order['pricing']['subtotal'] = 999;
    $order['pricing']['discount'] = 999;
    $order['pricing']['total'] = 999;
});
$result = $orderService->createOrder($input, $promoCodeContainer);
var_dump($result);
