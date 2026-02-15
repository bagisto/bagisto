<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Marketplace\Contracts\SellerOrder as SellerOrderContract;
use Webkul\Sales\Models\OrderProxy;
use Webkul\Sales\Models\OrderItemProxy;

class SellerOrder extends Model implements SellerOrderContract
{
    protected $table = 'marketplace_seller_orders';

    protected $fillable = [
        'order_id',
        'seller_id',
        'order_item_id',
        'product_id',
        'base_sub_total',
        'base_grand_total',
        'base_commission',
        'base_seller_total',
        'commission_percentage',
        'status',
    ];

    protected $casts = [
        'base_sub_total'        => 'decimal:4',
        'base_grand_total'      => 'decimal:4',
        'base_commission'       => 'decimal:4',
        'base_seller_total'     => 'decimal:4',
        'commission_percentage' => 'decimal:2',
    ];

    /**
     * Get the seller.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(SellerProxy::modelClass());
    }

    /**
     * Get the order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the order item.
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItemProxy::modelClass(), 'order_item_id');
    }
}
