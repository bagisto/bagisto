<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;
use Webkul\Product\Contracts\ProductGrid as ProductGridContract;

class ProductGrid extends Model implements ProductGridContract
{
    protected $table = 'products_grid';

    protected $fillable = ['product_id', 'sku', 'type', 'attribute_family_name', 'name', 'quantity', 'cost', 'price', 'status'];

    public $timestamps = false;

    public function product() {
        return $this->belongsTo(ProductProxy::modelClass(), 'product_id');
    }
}