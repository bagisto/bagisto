<?php

namespace Webkul\Marketplace\Enums;

/**
 * Per-seller money-routing mode, controlled by the marketplace admin.
 */
enum PayoutMode: string
{
    /** All order money stays with the platform; admin pays the seller manually (PIX/bank). */
    case Platform = 'platform';

    /** Seller earnings are settled to their connected Stripe account (Stripe Connect). */
    case Stripe = 'stripe';

    public function label(): string
    {
        return match ($this) {
            self::Platform => 'Platform collects (manual payout)',
            self::Stripe   => 'Stripe Connect (auto split)',
        };
    }

    public function isStripe(): bool
    {
        return $this === self::Stripe;
    }
}
