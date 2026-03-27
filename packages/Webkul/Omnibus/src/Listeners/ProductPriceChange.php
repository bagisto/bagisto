<?php

namespace Webkul\Omnibus\Listeners;

use Webkul\Omnibus\Services\OmnibusPriceManager;
use Webkul\Product\Contracts\Product;

class ProductPriceChange
{
    public function __construct(
        protected OmnibusPriceManager $omnibusPriceManager
    ) {}

    /**
     * Handle manual product save via Admin Panel.
     * Delegates price snapshot logic cleanly without duplications to the Shared Service Layer.
     *
     * @param  Product  $product
     */
    public function afterSave($product)
    {
        $this->omnibusPriceManager->recordPriceIfNeeded($product);
    }
}
