<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Inventory\Models\InventorySourceProxy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Product\Database\Factories\ProductInventoryFactory;
use Webkul\Product\Contracts\ProductInventory as ProductInventoryContract;

class ProductInventory extends Model implements ProductInventoryContract
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'qty',
        'product_id',
        'inventory_source_id',
        'vendor_id',
    ];

    /**
     * Get the product attribute family that owns the product.
     */
    public function inventory_source(): BelongsTo
    {
        return $this->belongsTo(InventorySourceProxy::modelClass());
    }

    /**
     * Get the product that owns the product inventory.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ProductInventoryFactory::new();
    }
}