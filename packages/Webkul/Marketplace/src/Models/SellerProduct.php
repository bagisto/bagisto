<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Marketplace\Contracts\SellerProduct as SellerProductContract;
use Webkul\Product\Models\Product;

class SellerProduct extends Model implements SellerProductContract
{
    protected $table = 'marketplace_seller_products';

    protected $fillable = [
        'seller_id',
        'product_id',
        'status',
        'is_owner',
    ];

    protected $casts = [
        'is_owner' => 'boolean',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
