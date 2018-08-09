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

class Product extends Model
{
    protected $fillable = ['type', 'attribute_family_id', 'sku', 'parent_id'];

    protected $with = ['attribute_family', 'attribute_values', 'variants', 'inventories'];

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
        return $this->belongsToMany(self::class, 'product_up_sells');
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
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (!method_exists(self::class, $key) && !isset($this->attributes[$key])) {
            if ($this->isCustomAttribute($key)) {
                $attributeModel = $this->attribute_family->custom_attributes()->where('attributes.code', $key)->first();

                if($attributeModel) {
                    if($attributeModel->value_per_channel) {
                        $channel = request()->get('channel') ?: channel()->getChannel();
                        if($attributeModel->value_per_locale) {
                            $locale = request()->get('locale') ?: app()->getLocale();
                            $attributeValue = $this->attribute_values()->where('channel', $channel)->where('locale', $locale)->where('attribute_id', $attributeModel->id)->first();
                        } else {
                            $attributeValue = $this->attribute_values()->where('channel', $channel)->where('attribute_id', $attributeModel->id)->first();
                        }
                    } else {
                        if($attributeModel->value_per_locale) {
                            $locale = request()->get('locale') ?: app()->getLocale();
                            $attributeValue = $this->attribute_values()->where('locale', $locale)->where('attribute_id', $attributeModel->id)->first();
                        } else {
                            $attributeValue = $this->attribute_values()->where('attribute_id', $attributeModel->id)->first();
                        }
                    }

                    if ($this->hasGetMutator($key)) {
                        $this->attributes[$key] = $attributeValue[ProductAttributeValue::$attributeTypeFields[$attributeModel->type]];

                        return $this->getAttributeValue($key);
                    }

                    return $attributeValue[ProductAttributeValue::$attributeTypeFields[$attributeModel->type]];
                }

                return $this->getAttributeValue($key);
            }

            return $this->getAttributeValue($key);
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

        foreach ($this->attribute_family->custom_attributes as $attribute) {
            if (in_array($attribute->code, $hiddenAttributes)) {
                continue;
            }

            if($attribute->value_per_channel) {
                $channel = request()->get('channel') ?: channel()->getChannel();
                if($attribute->value_per_locale) {
                    $locale = request()->get('locale') ?: app()->getLocale();
                    $attributeValue = $this->attribute_values()->where('channel', $channel)->where('locale', $locale)->where('attribute_id', $attribute->id)->first();
                } else {
                    $attributeValue = $this->attribute_values()->where('channel', $channel)->where('attribute_id', $attribute->id)->first();
                }
            } else {
                if($attribute->value_per_locale) {
                    $locale = request()->get('locale') ?: app()->getLocale();
                    $attributeValue = $this->attribute_values()->where('locale', $locale)->where('attribute_id', $attribute->id)->first();
                } else {
                    $attributeValue = $this->attribute_values()->where('attribute_id', $attribute->id)->first();
                }
            }

            if (!is_null($value = $attributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]])) {
                $attributes[$attribute->code] = $value;
            }
        }

        return $attributes;
    }
}