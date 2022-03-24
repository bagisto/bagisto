<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\RefundItem as RefundItemContract;

class RefundItem extends Model implements RefundItemContract
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'additional' => 'array',
    ];
    
    /**
     * Get the Refund record associated with the Refund item.
     */
    public function refund()
    {
        return $this->belongsTo(RefundProxy::modelClass());
    }

    /**
     * Get the order item record associated with the Refund item.
     */
    public function order_item()
    {
        return $this->belongsTo(OrderItemProxy::modelClass());
    }

    /**
     * Get the Refund record associated with the Refund item.
     */
    public function product()
    {
        return $this->morphTo();
    }

    /**
     * Get the child item record associated with the Refund item.
     */
    public function child()
    {
        return $this->hasOne(RefundItemProxy::modelClass(), 'parent_id');
    }
}