<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CartRuleCustomerGroups as CartRuleCustomerGroupContract;

class CartRuleCustomerGroups extends Model implements CartRuleCustomerGroupContract
{
    protected $table = 'cart_rule_customer_groups';

    protected $guarded = ['created_at', 'updated_at'];
}
