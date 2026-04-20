<?php

namespace Webkul\Omnibus\Listeners;

use Webkul\Omnibus\Services\OmnibusPriceManager;
use Webkul\Product\Contracts\Product;

class ProductPriceChange
{
    /**
     * Create a new listener instance.
     */
    public function __construct(
        protected OmnibusPriceManager $omnibusPriceManager
    ) {}

    /**
     * Record a price snapshot after a product is created or updated.
     */
    public function handle(Product $product): void
    {
        $this->omnibusPriceManager->recordPrice($product);
    }
}
