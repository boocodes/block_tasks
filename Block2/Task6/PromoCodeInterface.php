<?php
namespace Legacy;

interface PromoCodeInterface
{
    public function setPromoCode(string $promoCode, callable $callback): void;
    public function applyPromoCodes(): void;
}