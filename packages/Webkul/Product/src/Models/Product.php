<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Attribute\Models\AttributeFamily;
use Webkul\Category\Models\Category;
use Webkul\Attribute\Models\Attribute;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductInventory;
use Webkul\Product\Models\ProductImage;
use Webkul\Inventory\Models\InventorySource;
use Webkul\Product\Models\ProductReview;

class Product extends Model
{
    protected $fillable = ['type', 'attribute_family_id', 'sku', 'parent_id'];

    protected $with = ['attribute_family', 'inventories'];

    // protected $table = 'products';

    /**
     * Get the product attribute family that owns the product.
     */
    public function attribute_family()
    {
        return $this->belongsTo(AttributeFamily::class);
    }

    /**
     * Get the product attribute values that owns the product.
     */
    public function attribute_values()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /**
     * Get the product variants that owns the product.
     */
    public function variants()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get the product reviews that owns the product.
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Get the product that owns the product.
     */
    public function parent()
    {
        return $this->belongsTo(self::class);
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
        return $this->hasMany(ProductInventory::class, 'product_id');
    }

    /**
     * The inventory sources that belong to the product.
     */
    public function inventory_sources()
    {
        return $this->belongsToMany(InventorySource::class, 'product_inventories')->withPivot('id', 'qty');
    }

    /**
     * The super attributes that belong to the product.
     */
    public function super_attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_super_attributes');
    }

    /**
     * The images that belong to the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
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
        return $this->belongsToMany(self::class, 'product_up_sells', 'parent_id', 'child_id');
    }

    /**
     * The cross sells that belong to the product.
     */
    public function cross_sells()
    {
        return $this->belongsToMany(self::class, 'product_cross_sells');
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isCustomAttribute($attribute)
    {
        return $this->attribute_family->custom_attributes->pluck('code')->contains($attribute);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isSaleable()
    {
        if(!$this->status)
            return false;
        
        if($this->haveSufficientQuantity(1))
            return true;
    
        return false;
    }

    /**
     * @param integer $qty
     *
     * @return bool
     */
    public function haveSufficientQuantity($qty)
    {
        $inventories = $this->inventory_sources()->orderBy('priority', 'asc')->get();

        $total = 0;

        foreach($inventories as $inventorySource) {
            if(!$inventorySource->status)
                continue;

            $total += $inventorySource->pivot->qty;
        }

        return $qty <= $total ? true : false;
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (!method_exists(self::class, $key) && !in_array($key, ['parent_id', 'attribute_family_id']) && !isset($this->attributes[$key])) {
            if (isset($this->id) && $this->isCustomAttribute($key)) {
                $this->attributes[$key] = '';

                $attributeModel = $this->attribute_family->custom_attributes()->where('attributes.code', $key)->first();

                $this->attributes[$key] = $this->getCustomAttributeValue($attributeModel);

                return $this->getAttributeValue($key);
            }

        }

        return parent::getAttribute($key);
    }

    /**
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        $hiddenAttributes = $this->getHidden();

        if(isset($this->id)) {
            foreach ($this->attribute_family->custom_attributes as $attribute) {
                if (in_array($attribute->code, $hiddenAttributes)) {
                    continue;
                }

                $attributes[$attribute->code] = $this->getCustomAttributeValue($attribute);
            }
        }

        return $attributes;
    }

    /**
     * Get an product attribute value.
     *
     * @return mixed
     */
    public function getCustomAttributeValue($attribute)
    {
        if(!$attribute)
            return;

        $channel = request()->get('channel') ?: core()->getDefaultChannelCode();

        $locale = request()->get('locale') ?: app()->getLocale();

        if($attribute->value_per_channel) {
            if($attribute->value_per_locale) {
                $attributeValue = $this->attribute_values()->where('channel', $channel)->where('locale', $locale)->where('attribute_id', $attribute->id)->first();
            } else {
                $attributeValue = $this->attribute_values()->where('channel', $channel)->where('attribute_id', $attribute->id)->first();
            }
        } else {
            if($attribute->value_per_locale) {
                $attributeValue = $this->attribute_values()->where('locale', $locale)->where('attribute_id', $attribute->id)->first();
            } else {
                $attributeValue = $this->attribute_values()->where('attribute_id', $attribute->id)->first();
            }
        }

        return $attributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]];
    }

    /**
     * Overrides the default Eloquent query builder
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newEloquentBuilder($query)
    {
        return new \Webkul\Product\Database\Eloquent\Builder($query);
    }
    
}