<?php

namespace Webkul\Stripe\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Stripe\Contracts\StripeTransaction as StripeTransactionContract;
use Webkul\Stripe\Enums\StripeTransactionStatus;

class StripeTransaction extends Model implements StripeTransactionContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cart_id',
        'customer_id',
        'session_id',
        'amount',
        'status',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => StripeTransactionStatus::class,
    ];
}
