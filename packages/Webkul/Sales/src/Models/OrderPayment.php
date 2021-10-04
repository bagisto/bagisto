<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Sales\Database\Factories\OrderPaymentFactory;
use Webkul\Sales\Contracts\OrderPayment as OrderPaymentContract;

class OrderPayment extends Model implements OrderPaymentContract
{

    use HasFactory;

    protected $table = 'order_payment';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'additional' => 'array',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return OrderPaymentFactory
     */
    protected static function newFactory(): OrderPaymentFactory
    {
        return OrderPaymentFactory::new();
    }

}