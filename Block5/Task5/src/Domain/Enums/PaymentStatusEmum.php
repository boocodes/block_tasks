<?php
namespace StorageTask5\Domain\Enums;

enum PaymentStatusEmum: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
}