<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Attribute\Models\AttributeFamilyProxy;
use Webkul\Category\Models\CategoryProxy;
use Webkul\Attribute\Models\AttributeProxy;
use Webkul\Inventory\Models\InventorySourceProxy;
use Webkul\Product\Contracts\Product as ProductContract;

class Product extends Model implements ProductContract
{
    protected $fillable = ['type', 'attribute_family_id', 'sku', 'parent_id'];

    protected $typeInstance;

    // protected $with = ['attribute_family', 'inventories'];

    // protected $table = 'products';

    /**
     * Get the product attribute family that owns the product.
     */
    public function attribute_family()
    {
        return $this->belongsTo(AttributeFamilyProxy::modelClass());
    }

    /**
     * Get the product attribute values that owns the product.
     */
    public function attribute_values()
    {
        return $this->hasMany(ProductAttributeValueProxy::modelClass());
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
        return $this->hasMany(ProductReviewProxy::modelClass());
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
        return $this->belongsToMany(CategoryProxy::modelClass(), 'product_categories');
    }

    /**
     * The inventories that belong to the product.
     */
    public function inventories()
    {
        return $this->hasMany(ProductInventoryProxy::modelClass(), 'product_id');
    }

    /**
     * The ordered inventories that belong to the product.
     */
    public function ordered_inventories()
    {
        return $this->hasMany(ProductOrderedInventoryProxy::modelClass(), 'product_id');
    }

    /**
     * The inventory sources that belong to the product.
     */
    public function inventory_sources()
    {
        return $this->belongsToMany(InventorySourceProxy::modelClass(), 'product_inventories')->withPivot('id', 'qty');
    }

    /**
     * The super attributes that belong to the product.
     */
    public function super_attributes()
    {
        return $this->belongsToMany(AttributeProxy::modelClass(), 'product_super_attributes');
    }

    /**
     * The images that belong to the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImageProxy::modelClass(), 'product_id');
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
        return $this->belongsToMany(self::class, 'product_relations', 'parent_id', 'child_id')->limit(4);
    }

    /**
     * The up sells that belong to the product.
     */
    public function up_sells()
    {
        return $this->belongsToMany(self::class, 'product_up_sells', 'parent_id', 'child_id')->limit(4);
    }

    /**
     * The cross sells that belong to the product.
     */
    public function cross_sells()
    {
        return $this->belongsToMany(self::class, 'product_cross_sells', 'parent_id', 'child_id')->limit(4);
    }

    /**
     * The images that belong to the product.
     */
    public function downloadable_samples()
    {
        return $this->hasMany(ProductDownloadableSampleProxy::modelClass());
    }

    /**
     * The images that belong to the product.
     */
    public function downloadable_links()
    {
        return $this->hasMany(ProductDownloadableLinkProxy::modelClass());
    }

    /**
     * Get the grouped products that owns the product.
     */
    public function grouped_products()
    {
        return $this->hasMany(ProductGroupedProductProxy::modelClass());
    }

    /**
     * Get the bundle options that owns the product.
     */
    public function bundle_options()
    {
        return $this->hasMany(ProductBundleOptionProxy::modelClass());
    }

    /**
     * @param integer $qty
     *
     * @return bool
     */
    public function inventory_source_qty($inventorySourceId)
    {
        return $this->inventories()
                ->where('inventory_source_id', $inventorySourceId)
                ->sum('qty');
    }

    /**
     * Retrieve type instance
     *
     * @return AbstractType
     */
    public function getTypeInstance()
    {
        if ($this->typeInstance)
            return $this->typeInstance;

        $this->typeInstance = app(config('product_types.' . $this->type . '.class'));

        $this->typeInstance->setProduct($this);

        return $this->typeInstance;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isSaleable()
    {
        return $this->getTypeInstance()->isSaleable();
    }

    /**
     * @return integer
     */
    public function totalQuantity()
    {
        return $this->getTypeInstance()->totalQuantity();
    }

    /**
     * @param integer $qty
     *
     * @return bool
     */
    public function haveSufficientQuantity($qty)
    {
        return $this->getTypeInstance()->haveSufficientQuantity($qty);
    }

    /**
     * @return bool
     */
    public function isStockable()
    {
        return $this->getTypeInstance()->isStockable();
    }

    /**
     * Retrieve product attributes
     *
     * @param Group $group
     * @param bool  $skipSuperAttribute
     * @return Collection
     */
    public function getEditableAttributes($group = null, $skipSuperAttribute = true)
    {
        return $this->getTypeInstance()->getEditableAttributes($group, $skipSuperAttribute);
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (! method_exists(self::class, $key) && ! in_array($key, ['parent_id', 'attribute_family_id']) && ! isset($this->attributes[$key])) {
            if (isset($this->id)) {
                $this->attributes[$key] = '';

                $attribute = core()->getSingletonInstance(\Webkul\Attribute\Repositories\AttributeRepository::class)
                        ->getAttributeByCode($key);

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

        if (isset($this->id)) {
            $familyAttributes = core()->getSingletonInstance(\Webkul\Attribute\Repositories\AttributeRepository::class)
                    ->getFamilyAttributes($this->attribute_family);

            foreach ($familyAttributes as $attribute) {
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
        if (! $attribute)
            return;

        $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

        $locale = request()->get('locale') ?: app()->getLocale();

        if ($attribute->value_per_channel) {
            if ($attribute->value_per_locale) {
                $attributeValue = $this->attribute_values()->where('channel', $channel)->where('locale', $locale)->where('attribute_id', $attribute->id)->first();
            } else {
                $attributeValue = $this->attribute_values()->where('channel', $channel)->where('attribute_id', $attribute->id)->first();
            }
        } else {
            if ($attribute->value_per_locale) {
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

    /**
     * Return the product id attribute.
     */
    public function getProductIdAttribute()
    {
        return $this->id;
    }

    /**
     * Return the product attribute.
     */
    public function getProductAttribute()
    {
        return $this;
    }
}