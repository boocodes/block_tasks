<?php

namespace Task1;



class PromoCode implements PromoCodeInterface
{
    private int $promoCodeMaxCount;
    private int $currentPromoCodeAppliedCount;
    private int $discount;
    private int $deliveryCost;
    private string $promoCodesSeparator;
    private float $subtotal;
    private array $promoCodes = [];


    public function getDiscount(): int
    {
        return $this->discount;
    }
    public function getDeliveryCost(): int
    {
        return $this->deliveryCost;
    }
    public function getSubTotal(): float
    {
        return $this->subtotal;
    }
    public function __construct()
    {
        $this->promoCodesSeparator = "|";
        $this->promoCodeMaxCount = 2;
        $this->currentPromoCodeAppliedCount = 0;
        $this->discount = 0;
        $this->deliveryCost = 0;
        $this->subtotal = 0;
    }

    public function parsePromoCode(string $promoCode) : void
    {
        $promoCodesArray = explode($this->promoCodesSeparator, $promoCode);

        if(empty($promoCodesArray)) return;

        foreach ($promoCodesArray as $promoCode) {
            if($promoCode === 'WELCOME10') {
                $this->promoCodes['WELCOME10'] =  function (&$order) {
                    $order['pricing']['discount'] = $order['pricing']['total'] * 0.1;
                    $order['pricing']['total'] = $order['pricing']['total'] - $order['pricing']['discount'];
                };
            }
            else if($promoCode === 'VIP')
            {

                $this->promoCodes['VIP'] = function (&$order) {
                    if ($this->subtotal >= 2000) {
                        $order['pricing']['discount'] = 300;
                    } else {
                        $order['pricing']['discount'] = 100;
                    }
                    if($order['pricing']['subtotal'] - $order['pricing']['discount'] < 0)
                    {
                        $order['pricing']['total'] = 0;
                    }
                    else
                    {
                        $order['pricing']['total'] = $order['pricing']['subtotal'] - $order['pricing']['discount'];
                    }
                };

            }
            else if($promoCode === 'SHIPFREE')
            {
                $this->promoCodes['SHIPFREE'] = function (&$order) {
                    $order['pricing']['total'] = $order['pricing']['total'] - $order['pricing']['deliveryCost'];
                    $order['pricing']['deliveryCost'] = 0;
                };
            }
        }

    }

    public function setPromoCode(string $promoCode, callable $callback) : void
    {
        $this->promoCodes[$promoCode] = $callback;
    }

    public function applyPromoCode(array &$order) : void
    {
        if(empty($this->promoCodes)) return;
        foreach($this->promoCodes as $promoCode => $callback)
        {
            if($this->currentPromoCodeAppliedCount < $this->promoCodeMaxCount)
            {
                $callback($order);
            }
            else
            {
                break;
            }
        }
        $this->currentPromoCodeAppliedCount = 0;
        unset($promoCode);
    }

}