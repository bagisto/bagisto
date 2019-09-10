<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Contracts\ProductFlat as ProductFlatContract;

class ProductFlat extends Model implements ProductFlatContract
{
    protected $table = 'product_flat';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $timestamps = false;

    /**
     * Get the product attribute family that owns the product.
     */
    public function getAttributeFamilyAttribute()
    {
        return $this->product->attribute_family;
    }

    /**
     * Get the product that owns the attribute value.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get the product variants that owns the product.
     */
    public function variants()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Get product type value from base product
     */
    public function getTypeAttribute()
    {
        return $this->product->type;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isSaleable()
    {
        if (! $this->status)
            return false;

        if ($this->haveSufficientQuantity(1))
            return true;

        return false;
    }

    /**
     * @return integer
     */
    public function totalQuantity()
    {
        return $this->product->totalQuantity();
    }

    /**
     * @param integer $qty
     *
     * @return bool
     */
    public function haveSufficientQuantity($qty)
    {
        return $this->product->haveSufficientQuantity($qty);
    }

    /**
     * The images that belong to the product.
     */
    public function images()
    {
        return (ProductImageProxy::modelClass())
            ::where('product_images.product_id', $this->product_id)
            ->select('product_images.*');
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getImagesAttribute()
    {
        return $this->images()->get();
    }

    /**
     * The reviews that belong to the product.
     */
    public function reviews()
    {
        return (ProductReviewProxy::modelClass())
            ::where('product_reviews.product_id', $this->product_id)
            ->select('product_reviews.*');
    }

    /**
     * Get all of the reviews for the attribute groups.
     */
    public function getReviewsAttribute()
    {
        return $this->reviews()->get();
    }

    /**
     * The related products that belong to the product.
     */
    public function related_products()
    {
        return $this->product->related_products();
    }

    /**
     * The up sells that belong to the product.
     */
    public function up_sells()
    {
        return $this->product->up_sells();
    }

    /**
     * The cross sells that belong to the product.
     */
    public function cross_sells()
    {
        return $this->product->cross_sells();
    }
}