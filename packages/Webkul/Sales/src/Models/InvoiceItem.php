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
     * Retrieve type instance
     *
     * @return AbstractType
     */
    public function getTypeInstance()
    {
        return $this->order_item->getTypeInstance();
    }
    
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
     * Get the children items.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get order item type
     */
    public function getTypeAttribute()
    {
        return $this->order_item->type;
    }
}