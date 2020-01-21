<?php

namespace Webkul\CartRule\Models;

// use Webkul\Core\Eloquent\TranslatableModel;
use Illuminate\Database\Eloquent\Model;
use Webkul\CartRule\Contracts\CartRule as CartRuleContract;
use Webkul\Core\Models\ChannelProxy;
use Webkul\Customer\Models\CustomerGroupProxy;

// class CartRule extends TranslatableModel implements CartRuleContract
class CartRule extends Model implements CartRuleContract
{
    protected $fillable = ['name', 'description', 'starts_from', 'ends_till', 'status', 'coupon_type', 'use_auto_generation', 'usage_per_customer', 'uses_per_coupon', 'times_used', 'condition_type', 'conditions', 'actions', 'end_other_rules', 'uses_attribute_conditions', 'action_type', 'discount_amount', 'discount_quantity', 'discount_step', 'apply_to_shipping', 'free_shipping', 'sort_order'];

    protected $casts = [
        'conditions' => 'array',
    ];

    // public $translatedAttributes = ['name'];

    /**
     * Get the channels that owns the cart rule.
     */
    public function channels()
    {
        return $this->belongsToMany(ChannelProxy::modelClass(), 'cart_rule_channels');
    }

    /**
     * Get the customer groups that owns the cart rule.
     */
    public function customer_groups()
    {
        return $this->belongsToMany(CustomerGroupProxy::modelClass(), 'cart_rule_customer_groups');
    }

    /**
     * Get the coupons that owns the cart rule.
     */
    public function coupons()
    {
        return $this->hasOne(CartRuleCouponProxy::modelClass());
    }

    /**
     * Get primary coupon code for cart rule.
     */
    public function coupon_code()
    {
        return $this->coupons()->where('is_primary', 1);
    }

    /**
     * Get primary coupon code for cart rule.
     */
    public function getCouponCodeAttribute()
    {
        $coupon = $this->coupon_code()->first();

        if (! $coupon)
            return;

        return $coupon->code;
    }
}