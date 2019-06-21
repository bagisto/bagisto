<?php

namespace Webkul\StripeConnect\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\StripeConnect\Contracts\StripeCart as StripeCartContract;

class StripeCart extends Model implements StripeCartContract
{
    protected $table = 'stripe_cart';

    protected $fillable = [
        'cart_id', 'stripe_token'
    ];
}