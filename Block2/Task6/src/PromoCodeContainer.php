<?php

namespace Task6;

require_once 'PromoCodeInterface.php';

class PromoCodeContainer implements PromoCodeInterface
{
    private int $promoCodeMaxCount;
    private int $promoCodeCount;
    private string $promoCodesSeparator;

    private array $promoCodes = [];

    public function __construct()
    {
        $this->promoCodeCount = 0;
        $this->promoCodeMaxCount = 2;
        $this->promoCodesSeparator = "|";
    }


    public function parsePromoCodes(string $promoCode): void
    {
        // default promocodes
        $promoCodesArray = explode($this->promoCodesSeparator, $promoCode);
        if (empty($promoCodesArray)) return;

        foreach ($promoCodesArray as $promoCode) {
            if (strtoupper($promoCode) === 'WELCOME10') {
                $this->promoCodes['WELCOME10'] = function (&$order) {
                    $order['pricing']['discount'] = $order['pricing']['total'] * 0.1;
                    $order['pricing']['total'] = $order['pricing']['total'] - $order['pricing']['discount'];
                };
            } else if (strtoupper($promoCode) === 'VIP') {
                $this->promoCodes['VIP'] = function (&$order) {
                    if ($order['pricing']['subtotal'] >= 2000) {
                        $order['pricing']['discount'] = 300;
                    } else {
                        $order['pricing']['discount'] = 100;
                    }
                    if ($order['pricing']['subtotal'] - $order['pricing']['discount'] < 0) {
                        $order['pricing']['total'] = 0;
                    } else {
                        $order['pricing']['total'] = $order['pricing']['subtotal'] - $order['pricing']['discount'];
                    }
                };
            } else if (strtoupper($promoCode) === 'SHIPFREE') {
                $this->promoCodes['SHIPFREE'] = function (&$order) {
                    $order['pricing']['total'] = $order['pricing']['total'] - $order['pricing']['deliveryCost'];
                    $order['pricing']['deliveryCost'] = 0;
                };
            }
        }
    }

    public function setPromoCode(string $promoCode, callable $callback): void
    {
        $this->promoCodes[$promoCode] = $callback;
    }


    public function applyPromoCodes(&$order): void
    {
        $this->promoCodeCount = 0;
        if (empty($this->promoCodes)) return;
        foreach ($this->promoCodes as $code => $callback) {
            if ($this->promoCodeCount < $this->promoCodeMaxCount) {
                $callback($order);
                $this->promoCodeCount++;
            } else {
                break;
            }
        }
        unset($code);
    }
}