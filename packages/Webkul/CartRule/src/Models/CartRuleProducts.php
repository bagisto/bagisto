<?php

namespace Webkul\CartRule\Models;

use Illuminate\Database\Eloquent\Model;

class CartRuleProducts extends Model
{
    protected $fillable = [
        'cart_rule_id',
        'product_id',
    ];
}