<?php


class CreateOrderRequest
{
    public array $customer;
    public array $items;
    public array $payment;
    public array $delivery;
    public array $promoCode;
    public function __construct(array $inputData)
    {
        $this->customer = $inputData['customer'];
        $this->items = $inputData['items'];
        $this->payment = $inputData['payment'];
        $this->delivery = $inputData['delivery'];
        $this->promoCode = $inputData['promoCode'];
    }


}