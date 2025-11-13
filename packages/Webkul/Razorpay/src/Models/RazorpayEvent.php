<?php

namespace Webkul\Razorpay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Razorpay\Contracts\RazorpayEvent as RazorpayEventContract;
use Webkul\Razorpay\Enums\PaymentStatus;
use Webkul\Sales\Models\Order;

class RazorpayEvent extends Model implements RazorpayEventContract
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
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'razorpay_invoice_status' => PaymentStatus::class,
        ];
    }

    /**
     * Get the order that owns the razorpay event.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
