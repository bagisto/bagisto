<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CartRule as CartRuleContract;
use Webkul\Discount\Models\CartRuleChannelsProxy as CartRuleChannels;
use Webkul\Discount\Models\CartRuleCustomerGroupsProxy as CartRuleCustomerGroups;
use Webkul\Discount\Models\CartRuleCouponsProxy as CartRuleCoupons;
use Webkul\Discount\Models\CartRuleLabelsProxy as CartRuleLabels;

class CartRule extends Model implements CartRuleContract
{
    protected $table = 'cart_rules';

    protected $guarded = ['created_at', 'updated_at'];

    protected $with = ['channels', 'customer_groups', 'coupons', 'labels'];

    public function channels()
    {
        return $this->hasMany(CartRuleChannels::modelClass());
    }

    public function customer_groups()
    {
        return $this->hasMany(CartRuleCustomerGroups::modelClass());
    }

    public function coupons()
    {
        return $this->hasOne(CartRuleCoupons::modelClass());
    }

    public function labels()
    {
        return $this->hasMany(CartRuleLabels::modelClass());
    }
}