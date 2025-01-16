<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Contracts\ProductCustomizableOptionPrice as ProductCustomizableOptionPriceContract;

class ProductCustomizableOptionPrice extends Model implements ProductCustomizableOptionPriceContract
{
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
        'is_default',
        'is_user_defined',
        'label',
        'price',
        'product_customizable_option_id',
        'qty',
        'sort_order',
    ];

    /**
     * Get the customizable option that owns this resource.
     */
    public function customizable_option()
    {
        return $this->belongsTo(ProductBundleOptionProxy::modelClass(), 'product_customizable_option_id');
    }
}
