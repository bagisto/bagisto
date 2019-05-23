<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CartRule as CartRuleContract;
use Webkul\Discount\Models\CartRuleChannelsProxy as CartRuleChannels;
use Webkul\Discount\Models\CartRuleCustomerGroupsProxy as CartRuleCustomerGroups;

class CartRule extends Model implements CartRuleContract
{
    protected $table = 'cart_rules';

    protected $guarded = ['created_at', 'updated_at'];

    public function channels()
    {
        return $this->hasMany(CartRuleChannels::modelClass());
    }

    public function customer_groups()
    {
        return $this->hasMany(CartRuleCustomerGroups::modelClass());
    }
}