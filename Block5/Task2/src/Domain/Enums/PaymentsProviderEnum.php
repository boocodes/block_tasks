<?php

namespace StorageTask2\Domain\Enums;

enum PaymentsProviderEnum: string
{
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';
    case CASH = 'cash';
}