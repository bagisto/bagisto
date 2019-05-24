<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CartRuleLabels as CartRuleLabelsContract;
use Webkul\Discount\Models\CartRuleProxy as CartRule;

class CartRuleLabels extends Model implements CartRuleLabelsContract
{
    protected $table = 'cart_rule_labels';

    protected $guarded = ['created_at', 'updated_at'];

    public function cart_rule()
    {
        return $this->belongsTo(CartRule::modelClass(), 'cart_rule_id');
    }
}