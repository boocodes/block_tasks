<?php
namespace Task6;

interface PromoCodeInterface
{
    public function setPromoCode(string $promoCode, callable $callback): void;
    public function applyPromoCodes(&$order): void;
}