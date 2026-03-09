<?php
namespace StorageTask5\Domain\Enums;


enum OrderStatusEnum: string
{
    case NEW = 'new';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
}