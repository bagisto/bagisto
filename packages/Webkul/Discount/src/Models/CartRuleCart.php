<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CartRuleCart as CartRuleContract;


class CartRuleCart extends Model implements CartRuleContract
{
    protected $table = 'cart_rule_cart';

    protected $guarded = ['created_at', 'updated_at'];
}