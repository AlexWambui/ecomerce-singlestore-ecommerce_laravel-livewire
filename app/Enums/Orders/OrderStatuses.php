<?php

namespace App\Enums\Orders;

enum OrderStatuses: int
{
    case PENDING = 0;
    case PAID = 1;
    case DELIVERED = 2;
    case ON_HOLD = 3;
}
