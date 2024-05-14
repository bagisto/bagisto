<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Checkout\Contracts\CartShippingRate as CartShippingRateContract;
use Webkul\Checkout\Database\Factories\CartShippingRateFactory;

class CartShippingRate extends Model implements CartShippingRateContract
{
    use HasFactory;

    /**
     * Fillable property of the model.
     *
     * @var array
     */
    protected $fillable = [
        'carrier',
        'carrier_title',
        'method',
        'method_title',
        'method_description',
        'price',
        'base_price',
        'discount_amount',
        'base_discount_amount',
        'tax_percent',
        'tax_amount',
        'base_tax_amount',
        'price_incl_tax',
        'base_price_incl_tax',
        'applied_tax_rate',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function shipping_address()
    {
        return $this->belongsTo(CartAddressProxy::modelClass(), 'cart_address_id')
            ->where('address_type', CartAddress::ADDRESS_TYPE_SHIPPING);
    }

    /**
     * Create a new factory instance for the model
     */
    protected static function newFactory(): Factory
    {
        return CartShippingRateFactory::new();
    }
}
