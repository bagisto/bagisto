<?php

namespace Webkul\Razorpay\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Razorpay\Contracts\RazorpayTransaction as RazorpayTransactionContract;
use Webkul\Razorpay\Enums\PaymentStatus;

class RazorpayTransaction extends Model implements RazorpayTransactionContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cart_id',
        'order_id',
        'razorpay_receipt',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_invoice_status',
        'razorpay_signature',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'razorpay_invoice_status' => PaymentStatus::class,
    ];
}
