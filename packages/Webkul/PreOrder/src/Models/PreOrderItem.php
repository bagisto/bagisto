<?php

namespace Webkul\PreOrder\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\PreOrder\Contracts\PreOrderItem as PreOrderItemContract;
use Webkul\Sales\Models\OrderProxy;
use Webkul\Sales\Models\OrderItemProxy;

class PreOrderItem extends Model implements PreOrderItemContract
{
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['preorder_type', 'preorder_percent', 'status', 'email_sent', 'paid_amount', 'base_paid_amount', 'base_remaining_amount', 'order_id', 'order_item_id', 'payment_order_item_id', 'token'];

    protected $statusLabel = [
        'pending' => 'Pending',
        'processing' => 'Processing',
        'completed' => 'Completed'
    ];

    protected $typeLabel = [
        'partial' => 'Partial Payment',
        'complete' => 'Complete Payment'
    ];

    /**
     * Get the order record associated with the pre order item.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the order item record associated with the pre order item.
     */
    public function order_item()
    {
        return $this->belongsTo(OrderItemProxy::modelClass());
    }

    /**
     * Get the order item record associated with the pre order item.
     */
    public function payment_order_item()
    {
        return $this->belongsTo(OrderItemProxy::modelClass(), 'payment_order_item_id');
    }

    /**
     * Returns the status label from status code
     */
    public function getStatusLabelAttribute()
    {
        return $this->statusLabel[$this->status];
    }

    /**
     * Returns the type label from status code
     */
    public function getTypeLabelAttribute()
    {
        return $this->typeLabel[$this->preorder_type];
    }
}
