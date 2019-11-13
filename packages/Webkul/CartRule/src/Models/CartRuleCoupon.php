<?php

namespace Webkul\CartRule\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CartRule\Contracts\CartRuleCoupon as CartRuleCouponContract;

class CartRuleCoupon extends Model implements CartRuleCouponContract
{
    protected $guarded = ['created_at', 'updated_at'];

    /**
     * Get the cart rule that owns the cart rule coupon.
     */
    public function cart_rule()
    {
        return $this->belongsTo(CartRuleProxy::modelClass());
    }

    /**
     * Get the cart rule coupon usage that owns the cart rule coupon.
     */
    public function coupon_usage()
    {
        return $this->hasOne(CartRuleCouponUsage::modelClass());
    }
}