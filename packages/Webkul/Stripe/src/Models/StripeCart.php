<?php

namespace Webkul\Stripe\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Stripe\Contracts\StripeCart as StripeCartContract;

class StripeCart extends Model implements StripeCartContract
{
    protected $table = 'stripe_cart';

    protected $fillable = [
        'cart_id',
        'stripe_token',
    ];
}
