<?php

namespace Webkul\Marketplace\Enums;

enum SubscriptionStatus: string
{
    case Active    = 'active';
    case Cancelled = 'cancelled';
    case Expired   = 'expired';
    case Trialing  = 'trialing';

    public function isActive(): bool
    {
        return in_array($this, [self::Active, self::Trialing]);
    }
}
