<?php

namespace Webkul\CartRule\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CartRule\Contracts\CartRuleCoupon as CartRuleCouponContract;

class CartRuleCoupon extends Model implements CartRuleCouponContract
{
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
    public function cart_rule()
    {
        return $this->belongsTo(CartRuleProxy::modelClass());
    }
}