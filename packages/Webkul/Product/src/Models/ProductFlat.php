<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
// use Webkul\Attribute\Models\AttributeFamily;
// use Webkul\Category\Models\Category;
// use Webkul\Attribute\Models\Attribute;
// use Webkul\Product\Models\ProductAttributeValue;
// use Webkul\Product\Models\ProductInventory;
// use Webkul\Product\Models\ProductImage;
// use Webkul\Inventory\Models\InventorySource;
// use Webkul\Product\Models\ProductReview;

class ProductFlat extends Model
{
    protected $table = 'product_flat';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $timestamps = false;
}