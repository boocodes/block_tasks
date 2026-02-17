<?php
require_once "vendor/autoload.php";

use Task1\Notifier;
use Task1\OrderFile;
use Task1\OrderService;
use Task1\OrderValidator;
use Task1\PromoCode;



$inputOrderData = [
    'customer' => ['email' => 'example@mail.ru', 'name' => 'test'],
    'items' => [
        ['sku' => 'A1', 'title' => 'Carrot', 'price' => 200.00, 'qty' => 1],
        ['sku' => 'A1', 'title' => 'Potato', 'price' => 200.00, 'qty' => 1],

    ],
    'payment' => ['method' => 'cash', 'cardNumber' => ''],
    'delivery' => ['type' => 'courier', 'address' => ''],
    'promoCode' => '',
];

$orderValidator = new OrderValidator();

if(!$orderValidator->validate($inputOrderData)){
    return ['ok'=>$orderValidator->getResponseStatus(), 'error'=>$orderValidator->getResponseValue()];
}


$promoCodes = new PromoCode();

//$promoCodes->setPromoCode('WELCOME20', function (&$order) {
//    $order['pricing']['discount'] = $order['pricing']['total'] * 0.2;
//    $order['pricing']['total'] = $order['pricing']['total'] - $order['pricing']['discount'];
//});


$order = new OrderService();

$createdOrder = $order->createOrder($inputOrderData, $promoCodes);




$orderFile = new OrderFile();

$savedOrder = $orderFile->saveOrder($createdOrder);


if($savedOrder['status'] == 'ok')
{
    $notifier = new Notifier($createdOrder['customer']['email'], $order->getAdminEmail(), true);


    $customerMessage = 'Thanks! Your order ' . $createdOrder['id'] . ' total=' . $createdOrder['pricing']['total'] . PHP_EOL;

    $adminMessage = 'New order ' . $createdOrder['id'] . ' total=' . $createdOrder['pricing']['total'] . ' customer=' . $createdOrder['customer']['email'] . PHP_EOL;

    $notifier->notifyAdmin($adminMessage);
    $notifier->notifyCustomer($customerMessage);
}
else
{
    var_dump($savedOrder['message']);
}



