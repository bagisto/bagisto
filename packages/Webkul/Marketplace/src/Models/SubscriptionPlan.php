<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Marketplace\Contracts\SubscriptionPlan as SubscriptionPlanContract;

class SubscriptionPlan extends Model implements SubscriptionPlanContract
{
    protected $table = 'marketplace_subscription_plans';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'interval',
        'max_products',
        'commission_rate',
        'featured_listing',
        'analytics_access',
        'is_active',
        'sort_order',
        'stripe_price_id',
    ];

    protected $casts = [
        'featured_listing'  => 'boolean',
        'analytics_access'  => 'boolean',
        'is_active'         => 'boolean',
        'price'             => 'decimal:2',
        'commission_rate'   => 'decimal:2',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function hasUnlimitedProducts(): bool
    {
        return $this->max_products === -1;
    }
}
