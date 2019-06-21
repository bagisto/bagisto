<?php

namespace Webkul\StripeConnect\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\StripeConnect\Contracts\StripeConnect as StripeConnectContract;

class StripeConnect extends Model implements StripeConnectContract
{
    protected $table = 'stripe_companies';

    protected $fillable = [
        'access_token', 'refresh_token', 'stripe_publishable_key', 'stripe_user_id'
    ];
}