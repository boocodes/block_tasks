<?php

class OrderRequest
{
    public readonly array $customer;
    public readonly array $items;
    public readonly array $payment;
    public readonly array $delivery;
    public readonly string $promoCode;
    /*
     * @param array $input Ожидается структура:
     * [
     *   'customer' => ['email' => '...', 'name' => '...'],
     *   'items' => [
     *      ['sku' => 'A1', 'title' => 'Item', 'price' => 199.99, 'qty' => 2],
     *   ],
     *   'payment' => ['currency' => 'rub', 'method' => 'card|cash|invoice', 'cardNumber' => '....' (опц.)],
     *   'delivery' => ['type' => 'courier|pickup|post', 'address' => '...' (для courier/post)],
     *   'promoCode' => 'WELCOME10|VIP|FREESHIP' (опц.),
     * ]
     */
    public function __construct(array $data)
    {
        $this->customer = $data['customer'];
        $this->items = $data['items'];
        $this->payment = $data['payment'];
        $this->delivery = $data['delivery'];
        $this->promoCode = $data['promoCode'];
    }
}