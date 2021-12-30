<?php

namespace Webkul\Notification\Models;

use Webkul\Sales\Models\OrderProxy;
use Illuminate\Database\Eloquent\Model;
use Webkul\Notification\Contracts\Notification as NotificationContract;

class Notification extends Model implements NotificationContract
{
    protected $fillable = [
        'type',
        'read',
        'order_id'
    ];

    /**
     * Get Order Details.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }
}