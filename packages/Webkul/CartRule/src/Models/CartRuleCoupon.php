<?php

namespace Webkul\CartRule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Core\Database\Factories\CartRuleCouponFactory;
use Webkul\CartRule\Contracts\CartRuleCoupon as CartRuleCouponContract;

class CartRuleCoupon extends Model implements CartRuleCouponContract
{

    use HasFactory;

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
     * Get the cart rule that owns the cart rule coupon.
     */
    public function cart_rule(): BelongsTo
    {
        return $this->belongsTo(CartRuleProxy::modelClass());
    }

    /**
     * Create a new factory instance for the model
     *
     * @return CartRuleCouponFactory
     */
    protected static function newFactory(): CartRuleCouponFactory
    {
        return CartRuleCouponFactory::new();
    }

}