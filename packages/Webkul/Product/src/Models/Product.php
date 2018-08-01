<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Attribute\Models\Attribute;
use Webkul\Category\Models\Category;
use Webkul\Inventory\Models\InventorySource;
use Webkul\Attribute\Models\AttributeFamily;

class Product extends Model
{
    protected $fillable = ['type', 'attribute_family_id', 'sku'];

    protected $with = ['super_attributes'];

    /**
     * Get the product attribute family that owns the product.
     */
    public function attribute_family()
    {
        return $this->belongsTo(AttributeFamily::class);
    }

    /**
     * The categories that belong to the product.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    /**
     * The inventories that belong to the product.
     */
    public function inventories()
    {
        return $this->belongsToMany(InventorySource::class, 'product_inventories');
    }

    /**
     * The super attributes that belong to the product.
     */
    public function super_attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_super_attributes');
    }

    /**
     * The related products that belong to the product.
     */
    public function related_products()
    {
        return $this->belongsToMany(self::class, 'product_relations');
    }

    /**
     * The up sells that belong to the product.
     */
    public function up_sells()
    {
        return $this->belongsToMany(self::class, 'product_up_sells');
    }

    /**
     * The cross sells that belong to the product.
     */
    public function cross_sells()
    {
        return $this->belongsToMany(self::class, 'product_cross_sells');
    }

    public function  __get($name) {
        // if(array_key_exists($name, $this->data)) {
        //     return $this->data[$name];
        // }

        return null;
    }
}