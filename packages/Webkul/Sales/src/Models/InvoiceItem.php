<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\InvoiceItem as InvoiceItemContract;

class InvoiceItem extends Model implements InvoiceItemContract
{
    /**
     * Get the invoice record associated with the invoice item.
     */
    public function invoice()
    {
        return $this->belongsTo(InvoiceProxy::modelClass());
    }

    /**
     * Get the order item record associated with the invoice item.
     */
    public function order_item()
    {
        return $this->belongsTo(OrderItemProxy::modelClass());
    }

    /**
     * Get the invoice record associated with the invoice item.
     */
    public function product()
    {
        return $this->morphTo();
    }

    /**
     * Get the child item record associated with the invoice item.
     */
    public function child()
    {
        return $this->belongsTo(InvoiceItemProxy::modelClass(), 'parent_id');
    }
}