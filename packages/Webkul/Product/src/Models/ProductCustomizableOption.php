<?php

namespace Webkul\Product\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Product\Contracts\ProductCustomizableOption as ProductCustomizableOptionContract;

class ProductCustomizableOption extends TranslatableModel implements ProductCustomizableOptionContract
{
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
        'is_required',
        'max_characters',
        'product_id',
        'sort_order',
        'supported_file_extensions',
        'type',
    ];

    /**
     * Get the product that owns the option.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get the all the customizable option prices for this option.
     */
    public function customizable_option_prices()
    {
        return $this->hasMany(ProductCustomizableOptionPriceProxy::modelClass())
            ->orderBy('sort_order');
    }
}
