<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Contracts\ProductBundleOptionProduct as ProductBundleOptionProductContract;
use Webkul\Product\Database\Factories\ProductBundleOptionProductFactory;

class ProductBundleOptionProduct extends Model implements ProductBundleOptionProductContract
{
    use HasFactory;

    /**
     * Set timestamp false.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fillable property of the model.
     *
     * @var array
     */
    protected $fillable = [
        'qty',
        'is_user_defined',
        'sort_order',
        'is_default',
        'product_bundle_option_id',
        'product_id',
    ];

    /**
     * Get the bundle option that owns this resource.
     */
    public function bundle_option()
    {
        return $this->belongsTo(ProductBundleOptionProxy::modelClass(), 'product_bundle_option_id');
    }

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return ProductBundleOptionProductFactory::new();
    }
}
