<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\Invoice as InvoiceContract;

class Invoice extends Model implements InvoiceContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $statusLabel = [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'refunded' => 'Refunded',
    ];

    /**
     * Returns the status label from status code
     */
    public function getStatusLabelAttribute()
    {
        return isset($this->statusLabel[$this->state]) ? $this->statusLabel[$this->state] : '';
    }

    /**
     * Get the order that belongs to the invoice.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

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
     * Get the addresses for the shipment.
     */
    public function address()
    {
        return $this->belongsTo(OrderAddressProxy::modelClass(), 'order_address_id');
    }
}