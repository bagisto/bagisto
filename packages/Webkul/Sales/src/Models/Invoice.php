<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\Invoice as InvoiceContract;

class Invoice extends Model implements InvoiceContract
{
    /**
     * Get the invoice items record associated with the invoice.
     */
    public function items() {
        return $this->hasMany(InvoiceItemProxy::modelClass())->whereNull('parent_id');
    }

    /**
     * Get the customer record associated with the invoice.
     */
    public function customer()
    {
        return $this->morphTo();
    }

    /**
     * Get the channel record associated with the invoice.
     */
    public function channel()
    {
        return $this->morphTo();
    }

    /**
     * Get the addresses for the invoice.
     */
    public function addresses()
    {
        return $this->hasMany(OrderAddressProxy::modelClass());
    }

    /**
     * Get the biling address for the invoice.
     */
    public function billing_address()
    {
        return $this->addresses()->where('address_type', 'billing');
    }

    /**
     * Get billing address for the invoice.
     */
    public function getBillingAddressAttribute()
    {
        return $this->billing_address()->first();
    }

    /**
     * Get the shipping address for the invoice.
     */
    public function shipping_address()
    {
        return $this->addresses()->where('address_type', 'shipping');
    }

    /**
     * Get shipping address for the invoice.
     */
    public function getShippingAddressAttribute()
    {
        return $this->shipping_address()->first();
    }
}