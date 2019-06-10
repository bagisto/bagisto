<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CartRuleChannels as CartRuleChannelsContract;

class CartRuleChannels extends Model implements CartRuleChannelsContract
{
    protected $table = 'cart_rule_channels';

    protected $guarded = ['created_at', 'updated_at'];
}
