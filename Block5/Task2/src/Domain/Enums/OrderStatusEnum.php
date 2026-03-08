<?php
namespace StorageTask2\Domain\Enums;


enum OrderStatusEnum: string
{
    case NEW = 'new';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
}