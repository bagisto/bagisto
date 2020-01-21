<?php

namespace Webkul\CartRule\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CartRule\Contracts\CartRuleCustomer as CartRuleCustomerContract;

class CartRuleCustomer extends Model implements CartRuleCustomerContract
{
    public $timestamps = false;
    
    protected $fillable = ['times_used', 'cart_rule_id', 'customer_id'];
}