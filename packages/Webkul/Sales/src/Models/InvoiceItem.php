<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\InvoiceItem as InvoiceItemContract;

class InvoiceItem extends Model implements InvoiceItemContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'additional' => 'array',
    ];
    
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
        return $this->hasOne(InvoiceItemProxy::modelClass(), 'parent_id');
    }

    /**
     * Returns configurable option html
     */
    public function getOptionDetailHtml()
    {

        if($this->type == 'configurable' && isset($this->additional['attributes'])) {
            $labels = [];

            foreach($this->additional['attributes'] as $attribute) {
                $labels[] = $attribute['attribute_name'] . ' : ' . $attribute['option_label'];
            }

            return implode(', ', $labels);
        }
    }
}