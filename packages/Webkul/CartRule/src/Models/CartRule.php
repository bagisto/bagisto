<?php

namespace Webkul\CartRule\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Database\Factories\CartRuleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\CartRule\Contracts\CartRule as CartRuleContract;
use Webkul\Core\Models\ChannelProxy;
use Webkul\Customer\Models\CustomerGroupProxy;

class CartRule extends Model implements CartRuleContract
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'starts_from',
        'ends_till',
        'status',
        'coupon_type',
        'use_auto_generation',
        'usage_per_customer',
        'uses_per_coupon',
        'times_used',
        'condition_type',
        'conditions',
        'actions',
        'end_other_rules',
        'uses_attribute_conditions',
        'action_type',
        'discount_amount',
        'discount_quantity',
        'discount_step',
        'apply_to_shipping',
        'free_shipping',
        'sort_order',
    ];

    protected $casts = [
        'conditions' => 'array',
    ];

    /**
     * Get the channels that owns the cart rule.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cart_rule_channels(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ChannelProxy::modelClass(), 'cart_rule_channels');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @deprecated laravel standard should be used
     */
    public function channels(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->cart_rule_channels();
    }

    /**
     * Get the customer groups that owns the cart rule.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cart_rule_customer_groups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CustomerGroupProxy::modelClass(), 'cart_rule_customer_groups');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @deprecated laravel standard should be used
     */
    public function customer_groups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->cart_rule_customer_groups();
    }

    /**
     * Get the coupons that owns the cart rule.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cart_rule_coupon(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CartRuleCouponProxy::modelClass());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     *
     * @deprecated laravel standard should be used
     */
    public function coupons(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->cart_rule_coupon();
    }

    /**
     * Get primary coupon code for cart rule.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function coupon_code(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->cart_rule_coupon()->where('is_primary', 1);
    }

    /**
     * Get primary coupon code for cart rule.
     *
     * @return string|void
     */
    public function getCouponCodeAttribute()
    {
        $coupon = $this->coupon_code()->first();

        if (! $coupon) {
            return;
        }

        return $coupon->code;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return CartRuleFactory::new();
    }
}