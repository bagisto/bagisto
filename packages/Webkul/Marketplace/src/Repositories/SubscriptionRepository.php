<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\Subscription;
use Webkul\Marketplace\Enums\SubscriptionStatus;

class SubscriptionRepository extends Repository
{
    public function model(): string
    {
        return Subscription::class;
    }

    public function activateForSeller(int $sellerId, int $planId, ?string $stripeId = null): object
    {
        // Cancel any existing active subscription
        $this->model
            ->where('seller_id', $sellerId)
            ->whereIn('status', [SubscriptionStatus::Active->value, SubscriptionStatus::Trialing->value])
            ->update(['status' => SubscriptionStatus::Cancelled, 'cancelled_at' => now()]);

        return $this->create([
            'seller_id'              => $sellerId,
            'plan_id'                => $planId,
            'status'                 => SubscriptionStatus::Active,
            'stripe_subscription_id' => $stripeId,
            'current_period_start'   => now(),
            'current_period_end'     => now()->addMonth(),
        ]);
    }

    public function findActiveForSeller(int $sellerId): ?object
    {
        return $this->model
            ->where('seller_id', $sellerId)
            ->whereIn('status', [SubscriptionStatus::Active->value, SubscriptionStatus::Trialing->value])
            ->with('plan')
            ->latest()
            ->first();
    }
}
