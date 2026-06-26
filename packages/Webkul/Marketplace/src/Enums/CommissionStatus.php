<?php

namespace Webkul\Marketplace\Enums;

enum CommissionStatus: string
{
    case Pending  = 'pending';
    case Paid     = 'paid';
    case Refunded = 'refunded';
}
