<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Contracts\ProductFlat as ProductFlatContract;

class ProductFlat extends Model implements ProductFlatContract
{
    use Searchable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_flat';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Ignorable attributes.
     *
     * @var array
     */
    protected $ignorableAttributes = [
        'pivot',
        'parent_id',
        'attribute_family_id',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'products_index';
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (
            ! method_exists(static::class, $key)
            && ! in_array($key, $this->ignorableAttributes)
            && ! isset($this->attributes[$key])
            && isset($this->id)
        ) {
            $attribute = core()
                ->getSingletonInstance(AttributeRepository::class)
                ->getAttributeByCode($key);

            if ($attribute && ($attribute->value_per_channel || $attribute->value_per_locale)) {
                $defaultProduct = $this->getDefaultProduct();

                $this->attributes[$key] = $defaultProduct->attributes[$key];

                return $this->getAttributeValue($key);
            }
        }

        return parent::getAttribute($key);
    }

    /**
     * Get the product that owns the attribute value.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get default product.
     *
     * @return \Webkul\Product\Models\ProductFlat
     */
    public function getDefaultProduct()
    {
        static $loadedDefaultProducts = [];

        if (array_key_exists($this->product_id, $loadedDefaultProducts)) {
            return $loadedDefaultProducts[$this->product_id];
        }

        return $loadedDefaultProducts[$this->product_id] = $this->product
            ->product_flats()
            ->where('channel', core()->getDefaultChannelCode())
            ->where('locale', config('app.fallback_locale'))
            ->first();
    }

    /**
     * Get the product that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the product variants that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Get all of the attributes for the attribute groups.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getImagesAttribute()
    {
        return $this->images()->get();
    }

    /**
     * The images that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(ProductImageProxy::modelClass(), 'product_id', 'product_id')
            ->orderBy('position');
    }

    /**
     * The videos that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos()
    {
        return $this->product->videos()
            ->orderBy('position');
    }

    /**
     * Get all of the reviews for the attribute groups.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getReviewsAttribute()
    {
        return $this->reviews()->get();
    }

    /**
     * The reviews that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function reviews()
    {
        return (ProductReviewProxy::modelClass())
            ::where('product_reviews.product_id', $this->product_id)
            ->select('product_reviews.*');
    }

    /**
     * Get product type value from base product.
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        return $this->product->type;
    }

    /**
     * Retrieve type instance.
     *
     * @return \Webkul\Product\Type\AbstractType
     */
    public function getTypeInstance()
    {
        return $this->product->getTypeInstance();
    }

    /**
     * Get the product attribute family that owns the product.
     *
     * @return \Webkul\Attribute\Models\AttributeFamily
     */
    public function getAttributeFamilyAttribute()
    {
        return $this->product->attribute_family;
    }

    /**
     * Retrieve product attributes.
     *
     * @param  Group  $group
     * @param  bool  $skipSuperAttribute
     * @return Collection
     */
    public function getEditableAttributes($group = null, $skipSuperAttribute = true)
    {
        return $this->product->getEditableAttributes($group, $skipSuperAttribute);
    }

    /**
     * Total quantity.
     *
     * @return integer
     */
    public function totalQuantity()
    {
        return $this->product->totalQuantity();
    }

    /**
     * Is product have sufficient quantity.
     *
     * @param  int $qty
     * @return bool
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        return $this->product->haveSufficientQuantity($qty);
    }

    /**
     * Is product saleable.
     *
     * @param  string  $key
     * @return bool
     */
    public function isSaleable()
    {
        return $this->product->isSaleable();
    }

    /**
     * Is product stockable.
     *
     * @return bool
     */
    public function isStockable()
    {
        return $this->product->isStockable();
    }

    /**
     * The related products that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function related_products()
    {
        return $this->product->related_products();
    }

    /**
     * The up sells that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function up_sells()
    {
        return $this->product->up_sells();
    }

    /**
     * The cross sells that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cross_sells()
    {
        return $this->product->cross_sells();
    }

    /**
     * The images that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function downloadable_samples()
    {
        return $this->product->downloadable_samples();
    }

    /**
     * The images that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function downloadable_links()
    {
        return $this->product->downloadable_links();
    }

    /**
     * Get the grouped products that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grouped_products()
    {
        return $this->product->grouped_products();
    }

    /**
     * Get the grouped products by `sort_order` key that owns the product.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupedProductsBySortOrder()
    {
        return $this->product->grouped_products()->orderBy('sort_order');
    }

    /**
     * Get the bundle options that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bundle_options()
    {
        return $this->product->bundle_options();
    }
}
