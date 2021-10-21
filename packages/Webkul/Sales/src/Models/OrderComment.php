<?php

namespace Webkul\Sales\Models;

use Webkul\Checkout\Models\CartProxy;
use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\OrderComment as OrderCommentContract;

class OrderComment extends Model implements OrderCommentContract
{
    protected $fillable = [
        'comment',
        'customer_notified',
        'order_id',
    ];

    /**
     * Get the order record associated with the order comment.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }
}