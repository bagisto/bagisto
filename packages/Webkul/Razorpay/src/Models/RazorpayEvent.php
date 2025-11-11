<?php

namespace Webkul\Razorpay\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Razorpay\Contracts\RazorpayEvent as RazorpayEventContract;

class RazorpayEvent extends Model implements RazorpayEventContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'razorpay_event_id',
        'razorpay_invoice_id',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_invoice_status',
        'razorpay_invoice_receipt',
        'razorpay_signature',
    ];
}
