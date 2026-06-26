<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Marketplace\Contracts\Subscription as SubscriptionContract;
use Webkul\Marketplace\Enums\SubscriptionStatus;

class Subscription extends Model implements SubscriptionContract
{
    protected $table = 'marketplace_subscriptions';

    protected $fillable = [
        'seller_id',
        'plan_id',
        'status',
        'stripe_subscription_id',
        'trial_ends_at',
        'current_period_start',
        'current_period_end',
        'cancelled_at',
    ];

    protected $casts = [
        'status'               => SubscriptionStatus::class,
        'trial_ends_at'        => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end'   => 'datetime',
        'cancelled_at'         => 'datetime',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function isExpired(): bool
    {
        return $this->current_period_end?->isPast() && ! $this->isActive();
    }
}
