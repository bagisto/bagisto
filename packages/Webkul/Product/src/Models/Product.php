<?php

namespace Webkul\Product\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Webkul\Attribute\Models\AttributeFamilyProxy;
use Webkul\Attribute\Models\AttributeProxy;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\BookingProduct\Models\BookingProductProxy;
use Webkul\Category\Models\CategoryProxy;
use Webkul\Inventory\Models\InventorySourceProxy;
use Webkul\Product\Contracts\Product as ProductContract;
use Webkul\Product\Database\Eloquent\Builder;
use Webkul\Product\Database\Factories\ProductFactory;
use Webkul\Product\Type\AbstractType;

class Product extends Model implements ProductContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var $fillable
     */
    protected $fillable = [
        'type',
        'attribute_family_id',
        'sku',
        'parent_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var $casts
     */
    protected $casts = [
        'additional' => 'array',
    ];

    /**
     * The type of product.
     *
     * @var \Webkul\Product\Type\AbstractType
     */
    protected $typeInstance;

    /**
     * Loaded attribute values.
     *
     * @var array
     */
    public static $loadedAttributeValues = [];

    /**
     * The `booted` method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        parent::boot();

        static::deleting(function ($product) {
            foreach ($product->product_flats as $productFlat) {
                $productFlat->unsearchable();
            }

            foreach ($product->variants as $variant) {
                foreach ($variant->product_flats as $productFlat) {
                    $productFlat->unsearchable();
                }
            }
        });
    }

    /**
     * Get the product flat entries that are associated with product.
     * May be one for each locale and each channel.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product_flats(): HasMany
    {
        return $this->hasMany(ProductFlatProxy::modelClass(), 'product_id');
    }

    /**
     * Get the product that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * Get the product attribute family that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute_family(): BelongsTo
    {
        return $this->belongsTo(AttributeFamilyProxy::modelClass());
    }

    /**
     * The super attributes that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function super_attributes(): BelongsToMany
    {
        return $this->belongsToMany(AttributeProxy::modelClass(), 'product_super_attributes');
    }

    /**
     * Get the product attribute values that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attribute_values(): HasMany
    {
        return $this->hasMany(ProductAttributeValueProxy::modelClass());
    }

    /**
     * Get the product customer group prices that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customer_group_prices(): HasMany
    {
        return $this->hasMany(ProductCustomerGroupPriceProxy::modelClass());
    }

    /**
     * The categories that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CategoryProxy::modelClass(), 'product_categories');
    }

    /**
     * The images that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImageProxy::modelClass(), 'product_id')
            ->orderBy('position');
    }

    /**
     * The videos that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos(): HasMany
    {
        return $this->hasMany(ProductVideoProxy::modelClass(), 'product_id')
            ->orderBy('position');
    }

    /**
     * Get the product reviews that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReviewProxy::modelClass());
    }

    /**
     * The inventory sources that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function inventory_sources(): BelongsToMany
    {
        return $this->belongsToMany(InventorySourceProxy::modelClass(), 'product_inventories')
            ->withPivot('id', 'qty');
    }

    /**
     * Get inventory source quantity.
     *
     * @param  $inventorySourceId
     * @return bool
     */
    public function inventory_source_qty($inventorySourceId)
    {
        return $this->inventories()
            ->where('inventory_source_id', $inventorySourceId)
            ->sum('qty');
    }

    /**
     * The inventories that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventories(): HasMany
    {
        return $this->hasMany(ProductInventoryProxy::modelClass(), 'product_id');
    }

    /**
     * The ordered inventories that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ordered_inventories(): HasMany
    {
        return $this->hasMany(ProductOrderedInventoryProxy::modelClass(), 'product_id');
    }

    /**
     * Get the product variants that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Get the grouped products that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grouped_products(): HasMany
    {
        return $this->hasMany(ProductGroupedProductProxy::modelClass());
    }

    /**
     * The images that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function downloadable_samples(): HasMany
    {
        return $this->hasMany(ProductDownloadableSampleProxy::modelClass());
    }

    /**
     * The images that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function downloadable_links(): HasMany
    {
        return $this->hasMany(ProductDownloadableLinkProxy::modelClass());
    }

    /**
     * Get the bundle options that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bundle_options(): HasMany
    {
        return $this->hasMany(ProductBundleOptionProxy::modelClass());
    }

    /**
     * Get the booking that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function booking_product()
    {
        return $this->hasOne(BookingProductProxy::modelClass());
    }

    /**
     * The related products that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function related_products(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'product_relations', 'parent_id', 'child_id')
            ->limit(4);
    }

    /**
     * The up sells that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function up_sells(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'product_up_sells', 'parent_id', 'child_id')
            ->limit(4);
    }

    /**
     * The cross sells that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cross_sells(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'product_cross_sells', 'parent_id', 'child_id')
            ->limit(4);
    }

    /**
     * Is saleable.
     *
     * @param  string  $key
     * @return bool
     *
     * @throws \Exception
     */
    public function isSaleable(): bool
    {
        return $this->getTypeInstance()
            ->isSaleable();
    }

    /**
     * Is stockable.
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function isStockable(): bool
    {
        return $this->getTypeInstance()
            ->isStockable();
    }

    /**
     * Total quantity.
     *
     * @return integer
     *
     * @throws \Exception
     */
    public function totalQuantity(): int
    {
        return $this->getTypeInstance()
            ->totalQuantity();
    }

    /**
     * Have sufficient quantity.
     *
     * @param  int  $qty
     * @return bool
     *
     * @throws \Exception
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        return $this->getTypeInstance()
            ->haveSufficientQuantity($qty);
    }

    /**
     * Get type instance.
     *
     * @return AbstractType
     *
     * @throws \Exception
     */
    public function getTypeInstance(): AbstractType
    {
        if ($this->typeInstance) {
            return $this->typeInstance;
        }

        $this->typeInstance = app(config('product_types.' . $this->type . '.class'));

        if (! $this->typeInstance instanceof AbstractType) {
            throw new Exception("Please ensure the product type '{$this->type}' is configured in your application.");
        }

        $this->typeInstance->setProduct($this);

        return $this->typeInstance;
    }

    /**
     * Return the product id attribute.
     *
     * @return int
     */
    public function getProductIdAttribute()
    {
        return $this->id;
    }

    /**
     * Return the product attribute.
     *
     * @return self
     */
    public function getProductAttribute()
    {
        return $this;
    }

    /**
     * The images that belong to the product.
     *
     * @return string
     */
    public function getBaseImageUrlAttribute()
    {
        $image = $this->images()
            ->first();

        return $image->url ?? null;
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (! method_exists(static::class, $key)
            && ! in_array($key, [
                'pivot',
                'parent_id',
                'attribute_family_id',
            ])
            && ! isset($this->attributes[$key])) {
            if (isset($this->id)) {
                $this->attributes[$key] = '';

                $attribute = core()
                    ->getSingletonInstance(AttributeRepository::class)
                    ->getAttributeByCode($key);

                $this->attributes[$key] = $this->getCustomAttributeValue($attribute);

                return $this->getAttributeValue($key);
            }
        }

        return parent::getAttribute($key);
    }

    /**
     * Retrieve product attributes.
     *
     * @param  Group  $group
     * @param  bool  $skipSuperAttribute
     * @return \Illuminate\Support\Collection
     *
     * @throws \Exception
     */
    public function getEditableAttributes($group = null, $skipSuperAttribute = true): Collection
    {
        return $this->getTypeInstance()
            ->getEditableAttributes($group, $skipSuperAttribute);
    }

    /**
     * Get an product attribute value.
     *
     * @return mixed
     */
    public function getCustomAttributeValue($attribute)
    {
        if (! $attribute) {
            return;
        }

        $locale = core()->checkRequestedLocaleCodeInRequestedChannel();
        $channel = core()->getRequestedChannelCode();

        if (array_key_exists($this->id, self::$loadedAttributeValues)
            && array_key_exists($attribute->id, self::$loadedAttributeValues[$this->id])) {
            return self::$loadedAttributeValues[$this->id][$attribute->id];
        }

        if ($attribute->value_per_channel) {
            if ($attribute->value_per_locale) {
                $attributeValue = $this->attribute_values()
                    ->where('channel', $channel)
                    ->where('locale', $locale)
                    ->where('attribute_id', $attribute->id)
                    ->first();
            } else {
                $attributeValue = $this->attribute_values()
                    ->where('channel', $channel)
                    ->where('attribute_id', $attribute->id)
                    ->first();
            }
        } else {
            if ($attribute->value_per_locale) {
                $attributeValue = $this->attribute_values()
                    ->where('locale', $locale)
                    ->where('attribute_id', $attribute->id)
                    ->first();
            } else {
                $attributeValue = $this->attribute_values()
                    ->where('attribute_id', $attribute->id)
                    ->first();
            }
        }

        return self::$loadedAttributeValues[$this->id][$attribute->id] = $attributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]] ?? null;
    }

    /**
     * Attributes to array.
     *
     * @return array
     */
    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();

        $hiddenAttributes = $this->getHidden();

        if (isset($this->id)) {
            $familyAttributes = $this->checkInLoadedFamilyAttributes();

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
     * Check in loaded family attributes.
     *
     * @return object
     */
    public function checkInLoadedFamilyAttributes(): object
    {
        static $loadedFamilyAttributes = [];

        if (array_key_exists($this->attribute_family_id, $loadedFamilyAttributes)) {
            return $loadedFamilyAttributes[$this->attribute_family_id];
        }

        return $loadedFamilyAttributes[$this->attribute_family_id] = core()
            ->getSingletonInstance(AttributeRepository::class)
            ->getFamilyAttributes($this->attribute_family);
    }

    /**
     * Overrides the default Eloquent query builder.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Webkul\Product\Database\Eloquent\Builder
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * Refresh the loaded attribute values.
     *
     * @return void
     */
    public function refreshloadedAttributeValues(): void
    {
        self::$loadedAttributeValues = [];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory(): Factory
    {
        return ProductFactory::new ();
    }
}
