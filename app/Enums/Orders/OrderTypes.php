<?php

namespace App\Enums\Orders;

enum OrderTypes: int
{
    case ECOMMERCE = 0;
    case POS = 1;
}
