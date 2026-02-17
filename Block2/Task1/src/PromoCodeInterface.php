<?php

namespace Task1;



interface PromoCodeInterface
{
    public function parsePromoCode(string $promoCode) : void;
    public function setPromoCode(string $promoCode, callable $callback) : void;
    public function applyPromoCode(array &$order) : void;
    public function getSubTotal(): float;
    public function getDiscount(): int;
    public function getDeliveryCost(): int;
}