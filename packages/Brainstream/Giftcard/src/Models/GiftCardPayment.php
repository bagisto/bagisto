<?php

namespace Brainstream\Giftcard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'giftcard_number',
        'order_id',
        'payment_id',
        'payer_id',
        'payer_email',
        'amount',
        'currency',
        'status',
        'payment_data',
        'payment_type'
    ];
}
