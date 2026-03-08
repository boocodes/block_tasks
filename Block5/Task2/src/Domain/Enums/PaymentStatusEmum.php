<?php
namespace StorageTask2\Domain\Enums;

enum PaymentStatusEmum: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
}