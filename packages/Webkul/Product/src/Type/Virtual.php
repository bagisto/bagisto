<?php

namespace Webkul\Product\Type;

use Webkul\Product\Helpers\Indexers\Price\Virtual as VirtualIndexer;

class Virtual extends AbstractType
{
    /**
     * Skip attribute for virtual product type.
     *
     * @var array
     */
    protected $skipAttributes = [
        'length',
        'width',
        'height',
        'weight',
        'depth',
    ];

    /**
     * Is a stockable product type.
     *
     * @var bool
     */
    protected $isStockable = false;

    /**
     * Show quantity box.
     *
     * @var bool
     */
    protected $showQuantityBox = true;

    /**
     * Return true if this product type is saleable.
     *
     * @return bool
     */
    public function isSaleable()
    {
        if (! $this->product->status) {
            return false;
        }

        if (
            is_callable(config('products.isSaleable')) &&
            call_user_func(config('products.isSaleable'), $this->product) === false
        ) {
            return false;
        }

        if ($this->haveSufficientQuantity(1)) {
            return true;
        }

        return false;
    }

    /**
     * Have sufficient quantity.
     *
     * @param  int  $qty
     * @return bool
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        if (! $this->product->manage_stock){
            return true;
        }

        return $qty <= $this->totalQuantity();
    }

    /**
     * Get product maximum price.
     *
     * @return float
     */
    public function getMaximumPrice()
    {
        return $this->product->price;
    }

    /**
     * Returns price indexer class for a specific product type
     *
     * @return string
     */
    public function getPriceIndexer()
    {
        return app(VirtualIndexer::class);
    }
}
