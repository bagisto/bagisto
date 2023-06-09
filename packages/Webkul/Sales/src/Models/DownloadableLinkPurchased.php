<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\Sales\Contracts\DownloadableLinkPurchased as DownloadableLinkPurchasedContract;

class DownloadableLinkPurchased extends Model implements DownloadableLinkPurchasedContract
{
    protected $table = 'downloadable_link_purchased';

    protected $fillable = [
        'product_name',
        'name',
        'url',
        'file',
        'file_name',
        'type',
        'download_bought',
        'download_used',
        'status',
        'customer_id',
        'order_id',
        'order_item_id',
        'download_canceled',
    ];

    /**
     * Get the customer record associated with the item.
     */
    public function customer()
    {
        return $this->belongsTo(CustomerProxy::modelClass());
    }

    /**
     * Get the order record associated with the item.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the order item record associated with the item.
     */
    public function order_item()
    {
        return $this->belongsTo(OrderItemProxy::modelClass());
    }
}
