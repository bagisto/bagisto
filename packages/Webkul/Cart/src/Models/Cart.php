<?php

namespace Webkul\Cart\Models;

class Cart
{
    protected $table = 'cart';

    protected $fillable = ['customer_id','session_id','channel_id','coupon_code','is_gift'];

    protected $hidden = ['coupon_code'];
}
