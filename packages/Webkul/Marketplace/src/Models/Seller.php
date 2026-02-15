<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\Marketplace\Contracts\Seller as SellerContract;
use Webkul\Marketplace\Database\Factories\SellerFactory;

class Seller extends Model implements SellerContract
{
    use HasFactory;

    protected $table = 'marketplace_sellers';

    protected $fillable = [
        'customer_id',
        'shop_title',
        'url',
        'description',
        'logo',
        'banner',
        'commission_percentage',
        'is_approved',
        'status',
        'tax_vat_number',
        'phone',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'postcode',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'status'      => 'boolean',
    ];

    /**
     * Get the customer that owns the seller profile.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerProxy::modelClass(), 'customer_id');
    }

    /**
     * Get seller products.
     */
    public function products(): HasMany
    {
        return $this->hasMany(SellerProductProxy::modelClass());
    }

    /**
     * Get seller orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(SellerOrderProxy::modelClass());
    }

    /**
     * Get seller transactions.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(SellerTransactionProxy::modelClass());
    }

    /**
     * Get seller reviews.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(SellerReviewProxy::modelClass());
    }

    /**
     * Get approved reviews.
     */
    public function approvedReviews(): HasMany
    {
        return $this->reviews()->where('status', 'approved');
    }

    /**
     * Get average rating.
     */
    public function getAverageRatingAttribute(): float
    {
        return (float) $this->approvedReviews()->avg('rating') ?: 0;
    }

    /**
     * Get the effective commission percentage.
     */
    public function getEffectiveCommissionAttribute(): float
    {
        return $this->commission_percentage
            ?? (float) core()->getConfigData('marketplace.settings.general.commission_percentage')
            ?: 0;
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): SellerFactory
    {
        return SellerFactory::new();
    }
}
