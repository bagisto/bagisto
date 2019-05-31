<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Models\CartRuleProxy as CartRule;
use Webkul\Discount\Contracts\CartRuleCart as CartRuleCartContract;


class CartRuleCart extends Model implements CartRuleCartContract
{
    protected $table = 'cart_rule_cart';

    protected $guarded = ['created_at', 'updated_at'];

    protected $with = ['cart_rule'];

    public function cart_rule()
    {
        return $this->hasOne(CartRule::modelClass(), 'id', 'cart_rule_id');
    }
}