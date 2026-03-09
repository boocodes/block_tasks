<?php

namespace StorageTask5\Domain\Enums;

enum PaymentsProviderEnum: string
{
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';
    case CASH = 'cash';
}