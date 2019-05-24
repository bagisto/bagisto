<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CartRuleCouponsUsage as CartRuleCouponsUsageContract;
use Webkul\Discount\Models\CartRuleProxy as CartRule;

class CartRuleCouponsUsage extends Model implements CartRuleCouponsUsageContract
{
    protected $table = 'cart_rule_coupons_usage';

    protected $guarded = ['created_at', 'updated_at'];

    public function cart_rule()
    {
        return $this->belongsTo(CartRule::modelClass(), 'cart_rule_id');
    }
}