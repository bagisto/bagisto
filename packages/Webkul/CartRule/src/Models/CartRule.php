<?php

namespace Webkul\CartRule\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\CartRule\Contracts\CartRule as CartRuleContract;
use Webkul\CartRule\Database\Factories\CartRuleFactory;
use Webkul\Core\Models\ChannelProxy;
use Webkul\Customer\Models\CustomerGroupProxy;

class CartRule extends Model implements CartRuleContract
{
    use HasFactory;

    /**
     * Add fillable property to the model.
     *
     * @var array
     */
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

    /**
     * Cast the conditions to the array.
     *
     * @var array
     */
    protected $casts = [
        'conditions' => 'array',
        'status' => 'boolean',
        'coupon_type' => 'integer',
        'use_auto_generation' => 'boolean',
        'condition_type' => 'integer',
        'end_other_rules' => 'boolean',
        'uses_attribute_conditions' => 'boolean',
        'apply_to_shipping' => 'boolean',
        'free_shipping' => 'boolean',
        'usage_per_customer' => 'integer',
        'uses_per_coupon' => 'integer',
        'times_used' => 'integer',
        'discount_quantity' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Set starts from with empty string to null conversion.
     */
    public function setStartsFromAttribute($value): void
    {
        $this->attributes['starts_from'] = $value !== '' && $value !== null ? $value : null;
    }

    /**
     * Set ends till with empty string to null conversion.
     */
    public function setEndsTillAttribute($value): void
    {
        $this->attributes['ends_till'] = $value !== '' && $value !== null ? $value : null;
    }

    /**
     * Set status with proper boolean conversion.
     */
    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Set use auto generation with proper boolean conversion.
     */
    public function setUseAutoGenerationAttribute($value): void
    {
        $this->attributes['use_auto_generation'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Set end other rules with proper boolean conversion.
     */
    public function setEndOtherRulesAttribute($value): void
    {
        $this->attributes['end_other_rules'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Set uses attribute conditions with proper boolean conversion.
     */
    public function setUsesAttributeConditionsAttribute($value): void
    {
        $this->attributes['uses_attribute_conditions'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Set apply to shipping with proper boolean conversion.
     */
    public function setApplyToShippingAttribute($value): void
    {
        $this->attributes['apply_to_shipping'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Set free shipping with proper boolean conversion.
     */
    public function setFreeShippingAttribute($value): void
    {
        $this->attributes['free_shipping'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Set usage per customer with empty string to zero conversion.
     */
    public function setUsagePerCustomerAttribute($value): void
    {
        $this->attributes['usage_per_customer'] = $value !== '' && $value !== null ? (int) $value : 0;
    }

    /**
     * Get the channels that owns the cart rule.
     */
    public function cart_rule_channels(): BelongsToMany
    {
        return $this->belongsToMany(ChannelProxy::modelClass(), 'cart_rule_channels');
    }

    /**
     * @deprecated laravel standard should be used
     */
    public function channels(): BelongsToMany
    {
        return $this->cart_rule_channels();
    }

    /**
     * Get the customer groups that owns the cart rule.
     */
    public function cart_rule_customer_groups(): BelongsToMany
    {
        return $this->belongsToMany(CustomerGroupProxy::modelClass(), 'cart_rule_customer_groups');
    }

    /**
     * @deprecated laravel standard should be used
     */
    public function customer_groups(): BelongsToMany
    {
        return $this->cart_rule_customer_groups();
    }

    /**
     * Get the coupons that owns the cart rule.
     */
    public function cart_rule_coupon(): HasOne
    {
        return $this->hasOne(CartRuleCouponProxy::modelClass());
    }

    /**
     * @deprecated laravel standard should be used
     */
    public function coupons(): HasOne
    {
        return $this->cart_rule_coupon();
    }

    /**
     * Get primary coupon code for cart rule.
     */
    public function coupon_code(): HasOne
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
     */
    protected static function newFactory(): Factory
    {
        return CartRuleFactory::new();
    }
}
