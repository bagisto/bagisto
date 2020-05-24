<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Checkout\Contracts\CartPayment as CartPaymentContract;

class CartPayment extends Model implements CartPaymentContract
{
    protected $table = 'cart_payment';
}