<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CartRuleCoupons as CartRuleCouponsContract;
use Webkul\Discount\Models\CartRuleProxy as CartRule;

class CartRuleCoupons extends Model implements CartRuleCouponsContract
{
    protected $table = 'cart_rule_coupons';

    protected $guarded = ['created_at', 'updated_at'];

    public function cart_rule()
    {
        return $this->belongsTo(CartRule::modelClass(), 'cart_rule_id');
    }

    public function coupons_usage()
    {
        return $this->hasOne(CartRuleCouponsUsage::modelClass());
    }
}