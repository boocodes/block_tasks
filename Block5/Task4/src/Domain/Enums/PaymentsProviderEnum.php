<?php

namespace StorageTask4\Domain\Enums;

enum PaymentsProviderEnum: string
{
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';
    case CASH = 'cash';
}