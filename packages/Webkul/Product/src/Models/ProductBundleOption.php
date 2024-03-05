<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Product\Contracts\ProductBundleOption as ProductBundleOptionContract;
use Webkul\Product\Database\Factories\ProductBundleOptionsFactory;

class ProductBundleOption extends TranslatableModel implements ProductBundleOptionContract
{
    use HasFactory;

    /**
     * Set timestamp false.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Add the translateable attribute.
     *
     * @var array
     */
    public $translatedAttributes = ['label'];

    /**
     * Add fillable property to the model.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'is_required',
        'sort_order',
        'product_id',
    ];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get the bundle option products that owns the bundle option.
     */
    public function bundle_option_products()
    {
        return $this->hasMany(ProductBundleOptionProductProxy::modelClass());
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return ProductBundleOptionsFactory::new();
    }
}
