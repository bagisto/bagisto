<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Checkout\Database\Factories\CartPaymentFactory;
use Webkul\Checkout\Contracts\CartPayment as CartPaymentContract;

class CartPayment extends Model implements CartPaymentContract
{

    use HasFactory;

    protected $table = 'cart_payment';

    /**
     * Create a new factory instance for the model
     *
     * @return CartPaymentFactory
     */
    protected static function newFactory(): CartPaymentFactory
    {
        return CartPaymentFactory::new();
    }

}