<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CartRule as CartRuleContract;

class CartRule extends Model implements CartRuleContract
{
    protected $table = 'cart_rules';

    protected $guarded = ['created_at', 'updated_at'];
}