<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Marketplace\Contracts\SellerProduct as SellerProductContract;
use Webkul\Product\Models\ProductProxy;

class SellerProduct extends Model implements SellerProductContract
{
    protected $table = 'marketplace_seller_products';

    protected $fillable = [
        'seller_id',
        'product_id',
        'is_approved',
        'condition',
        'price',
        'description',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'price'       => 'decimal:4',
    ];

    /**
     * Get the seller.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(SellerProxy::modelClass());
    }

    /**
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}
