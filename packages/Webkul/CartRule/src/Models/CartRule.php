<?php

namespace Webkul\CartRule\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\CartRule\Contracts\CartRule as CartRuleContract;

class CartRule extends TranslatableModel implements CartRuleContract
{
    protected $guarded = ['created_at', 'updated_at'];

    public $translatedAttributes = ['name'];

    /**
     * Get the channels that owns the cart rule.
     */
    public function channels()
    {
        return $this->hasMany(CartRuleChannelProxy::modelClass());
    }

    /**
     * Get the customer groups that owns the cart rule.
     */
    public function customer_groups()
    {
        return $this->hasMany(CartRuleCustomerGroupProxy::modelClass());
    }

    /**
     * Get the coupons that owns the cart rule.
     */
    public function coupons()
    {
        return $this->hasOne(CartRuleCouponProxy::modelClass());
    }
}