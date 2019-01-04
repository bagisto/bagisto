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
        return $this->belongsTo(self::class, 'parent_id');
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
     * The ordered inventories that belong to the product.
     */
    public function ordered_inventories()
    {
        return $this->hasMany(ProductOrderedInventory::class, 'product_id');
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
     * The images that belong to the product.
     */
    public function getBaseImageUrlAttribute()
    {
        $image = $this->images()->first();

        return $image ? $image->url : null;
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
    public function inventory_source_qty($inventorySource)
    {
        return $this->inventories()
                ->where('inventory_source_id', $inventorySource->id)
                ->sum('qty');
    }

    /**
     * @param integer $qty
     *
     * @return bool
     */
    public function haveSufficientQuantity($qty)
    {
        $total = 0;

        $channelInventorySourceIds = core()->getCurrentChannel()
                ->inventory_sources()
                ->where('status', 1)
                ->pluck('id');

        foreach ($this->inventories as $inventory) {
            if(is_numeric($index = $channelInventorySourceIds->search($inventory->inventory_source_id))) {
                $total += $inventory->qty;
            }
        }

        $orderedInventory = $this->ordered_inventories()
                ->where('channel_id', core()->getCurrentChannel()->id)
                ->first();

        if ($orderedInventory) {
            $total -= $orderedInventory->qty;
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
            if (isset($this->id)) {
                $this->attributes[$key] = '';

                $attribute = app(\Webkul\Attribute\Repositories\AttributeRepository::class)->findOneByField('code', $key);

                $this->attributes[$key] = $this->getCustomAttributeValue($attribute);

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

        $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

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