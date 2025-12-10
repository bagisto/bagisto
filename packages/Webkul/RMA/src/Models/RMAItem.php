<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductProxy;
use Webkul\RMA\Contracts\RMAItem as RMAItemContract;
use Webkul\Sales\Models\OrderItemProxy;

class RMAItem extends Model implements RMAItemContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rma_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rma_id',
        'quantity',
        'order_item_id',
        'resolution',
        'rma_reason_id',
        'variant_id',
    ];

    /**
     * Get Product Details
     *
     * @return object
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, 'id', 'variant_id');
    }

    /**
     * Get related RMA
     */
    public function rma(): BelongsTo
    {

        return $this->belongsTo(RMAProxy::modelClass(), 'rma_id');
    }

    /**
     * Get the order item related to the rma item.
     */
    public function getOrderItem(): HasMany
    {
        return $this->hasMany(OrderItemProxy::modelClass(), 'id', 'order_item_id');
    }

    /**
     * Get the order item related to the rma item.
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItemProxy::modelClass(), 'order_item_id', 'id');
    }

    /**
     * Get related product.
     */
    public function product()
    {
        return $this->hasOneThrough(
            ProductProxy::modelClass(),   // Final model
            OrderItemProxy::modelClass(), // Intermediate model
            'id',                         // Foreign key on order items table
            'id',                         // Foreign key on products table
            'order_item_id',              // Local key on RmaItem table
            'product_id'                  // Local key on order items table
        );
    }

    /**
     * Get the reasons related to the rma item.
     */
    public function getReasons(): HasOne
    {
        return $this->hasOne(RMAReasonProxy::modelClass(), 'id', 'rma_reason_id');
    }
}
