<?php

namespace Webkul\CartRule\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CartRule\Contracts\CartRuleCouponUsage as CartRuleCouponUsageContract;

class CartRuleCouponUsage extends Model implements CartRuleCouponUsageContract
{
    protected $table = 'cart_rule_coupons_usage';

    protected $guarded = ['created_at', 'updated_at'];
}