<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\Customer\Models\Customer;
use Webkul\Marketplace\Contracts\Seller as SellerContract;
use Webkul\Marketplace\Enums\SellerStatus;

class Seller extends Model implements SellerContract
{
    protected $table = 'marketplace_sellers';

    protected $fillable = [
        'customer_id',
        'shop_name',
        'shop_url',
        'logo',
        'banner',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'phone',
        'address',
        'country',
        'state',
        'city',
        'postcode',
        'commission_rate',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'status'       => SellerStatus::class,
        'is_featured'  => 'boolean',
        'commission_rate' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(SellerProduct::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(MarketplaceOrder::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }

    public function isApproved(): bool
    {
        return $this->status === SellerStatus::Approved;
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscription?->status?->isActive() ?? false;
    }

    public function pendingEarnings(): float
    {
        return $this->orders()
            ->where('commission_status', 'pending')
            ->sum('seller_total');
    }
}
