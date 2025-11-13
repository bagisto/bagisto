<?php

namespace Webkul\Stripe\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Stripe\Contracts\Stripe as StripeContract;

class Stripe extends Model implements StripeContract
{
    protected $table = 'stripe_cards';

    protected $fillable = [
        'token',
        'need_new_token',
        'customer_id',
        'last_four',
        'fingerprint',
        'misc',
    ];
}
