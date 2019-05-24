<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CartRuleCustomers as CartRuleCustomersContract;
use Webkul\Discount\Models\CartRuleProxy as CartRule;

class CartRuleCustomers extends Model implements CartRuleCustomersContract
{
    protected $table = 'cart_rule_customers';

    protected $guarded = ['created_at', 'updated_at'];

    public function cart_rule()
    {
        return $this->belongsTo(CartRule::modelClass(), 'cart_rule_id');
    }
}