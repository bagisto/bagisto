<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\Order as OrderContract;

class Order extends Model implements OrderContract
{
    protected $guarded = ['id', 'items', 'shipping_address', 'billing_address', 'payment', 'created_at', 'updated_at'];

    /**
     * Get the order items record associated with the order.
     */
    public function items() {
        return $this->hasMany(CartItemProxy::modelClass())->whereNull('parent_id');
    }

    /**
     * Get the order shipments record associated with the order.
     */
    public function shipments() {
        return $this->hasMany(ShipmentProxy::modelClass());
    }

    /**
     * Get the order invoices record associated with the order.
     */
    public function invoices() {
        return $this->hasMany(InvoiceProxy::modelClass());
    }
    
    /**
     * Get the customer record associated with the order.
     */
    public function customer()
    {
        return $this->morphTo();
    }

    /**
     * Get the addresses for the order.
     */
    public function addresses()
    {
        return $this->hasMany(OrderAddressProxy::modelClass());
    }

    /**
     * Get the payment for the order.
     */
    public function payment()
    {
        return $this->hasMany(OrderPaymentProxy::modelClass());
    }

    /**
     * Get the biling address for the order.
     */
    public function billing_address()
    {
        return $this->addresses()->where('address_type', 'billing');
    }

    /**
     * Get billing address for the order.
     */
    public function getBillingAddressAttribute()
    {
        return $this->billing_address()->first();
    }

    /**
     * Get the shipping address for the order.
     */
    public function shipping_address()
    {
        return $this->addresses()->where('address_type', 'shipping');
    }

    /**
     * Get shipping address for the order.
     */
    public function getShippingAddressAttribute()
    {
        return $this->shipping_address()->first();
    }

    /**
     * Get the channel record associated with the order.
     */
    public function channel()
    {
        return $this->morphTo();
    }
}