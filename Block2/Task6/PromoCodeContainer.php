<?php
namespace Legacy;
require_once 'PromoCodeInterface.php';

class PromoCodeContainer implements PromoCodeInterface {
    private int $promoCodeMaxCount;
    private int $promoCodeCount;
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
    public function getSubtotal(): float
    {
        return $this->subtotal;
    }
    public function __construct(int $promoCodeMaxCount, int $discount, int $deliveryCost, float $subtotal)
    {
        $this->promoCodeCount = 0;
        $this->promoCodeMaxCount = $promoCodeMaxCount;
        $this->discount = $discount;
        $this->deliveryCost = $deliveryCost;
        $this->subtotal = $subtotal;
        $this->promoCodesSeparator = "|";
    }


    public function parsePromoCodes(string $promoCode): void
    {
        // default promocodes
        $promoCodesString = isset($input['promoCode']) ? strtoupper(trim((string)$input['promoCode'])) : '';
        $promoCodesArray = explode($this->promoCodesSeparator, $promoCodesString);


        foreach ($promoCodesArray as $promoCode) {
            if($promoCode === 'WELCOME10') {
                $this->promoCodes['WELCOME10'] =  function () {
                    $this->discount = $this->subtotal * 0.10;
                };
            }
            else if($promoCode === 'VIP')
            {
                $this->promoCodes['VIP'] = function () {
                    if ($this->subtotal >= 2000) {
                        $this->discount = 300;
                    } else {
                        $this->discount = 100;
                    }
                };
            }
            else if($promoCode === 'SHIPFREE')
            {
                $this->promoCodes['SHIPFREE'] = function () {
                    $this->deliveryCost = 0;
                };
            }
        }
    }

    public function setPromoCode(string $promoCode, callable $callback): void
    {
        $this->promoCodes[$promoCode] = $callback;
    }


    public function applyPromoCodes(): void
    {
        if(empty($this->promoCodes)) return;
        foreach ($this->promoCodes as $code => $callback)
        {
            if($this->promoCodeCount < $this->promoCodeMaxCount)
            {
                $code();
                $this->promoCodeCount++;
            }
            else
            {
                break;
            }
        }
        unset($code);
    }
}