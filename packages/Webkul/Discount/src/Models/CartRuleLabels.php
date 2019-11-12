<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CartRuleLabels as CartRuleLabelsContract;
use Webkul\Discount\Models\CartRuleProxy as CartRule;
use Webkul\Core\Models\LocaleProxy as Locale;
use Webkul\Core\Models\ChannelProxy as Channel;

class CartRuleLabels extends Model implements CartRuleLabelsContract
{
    protected $table = 'cart_rule_labels';

    protected $guarded = ['created_at', 'updated_at'];

    protected $with = ['locale', 'channel'];

    public function cart_rule()
    {
        return $this->belongsTo(CartRule::modelClass(), 'cart_rule_id');
    }

    public function locale()
    {
        return $this->hasOne(Locale::modelClass(), 'id', 'locale_id');
    }

    public function channel()
    {
        return $this->hasOne(Channel::modelClass(), 'id', 'channel_id');
    }
}