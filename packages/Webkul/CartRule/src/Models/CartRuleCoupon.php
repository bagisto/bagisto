<?php

namespace Webkul\CartRule\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\CartRule\Contracts\CartRuleCoupon as CartRuleCouponContract;
use Webkul\CartRule\Database\Factories\CartRuleCouponFactory;

class CartRuleCoupon extends Model implements CartRuleCouponContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'usage_limit',
        'usage_per_customer',
        'times_used',
        'type',
        'cart_rule_id',
        'expired_at',
        'is_primary',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'usage_limit' => 'integer',
        'usage_per_customer' => 'integer',
        'times_used' => 'integer',
        'is_primary' => 'boolean',
        'expired_at' => 'datetime',
    ];

    /**
     * Set expired at with empty string to null conversion.
     */
    public function setExpiredAtAttribute($value): void
    {
        $this->attributes['expired_at'] = $value !== '' && $value !== null ? $value : null;
    }

    /**
     * Get the cart rule that owns the cart rule coupon.
     */
    public function cart_rule(): BelongsTo
    {
        return $this->belongsTo(CartRuleProxy::modelClass());
    }

    /**
     * Get the cart rule that owns the cart rule coupon.
     */
    public function coupon_usage()
    {
        return $this->hasMany(CartRuleCouponUsageProxy::modelClass());
    }

    /**
     * Create a new factory instance for the model
     */
    protected static function newFactory(): Factory
    {
        return CartRuleCouponFactory::new();
    }
}
