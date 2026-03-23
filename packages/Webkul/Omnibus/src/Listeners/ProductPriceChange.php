<?php

namespace Webkul\Omnibus\Listeners;

use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Product\Contracts\Product;
use Webkul\Product\Repositories\ProductRepository;

class ProductPriceChange
{
    public function __construct(
        protected OmnibusPriceRepository $omnibusPriceRepository,
        protected ProductRepository $productRepository
    ) {}

    /**
     * Handle product saving event tracking
     *
     * @param  Product  $product
     */
    public function afterSave($product)
    {
        if (! core()->getConfigData('catalog.products.omnibus.is_enabled')) {
            return;
        }

        if ($product->type === 'configurable') {
            foreach ($product->variants as $variant) {
                $this->checkAndSavePrice($variant);
            }
        }

        $this->checkAndSavePrice($product);
    }

    /**
     * Helper to save price history.
     *
     * @param  Product  $product
     */
    protected function checkAndSavePrice($product)
    {
        $currentPrice = $product->price;
        $currentSpecialPrice = $product->special_price;

        $lastHistory = $this->omnibusPriceRepository->getLatestByProductId($product->id);

        $hasChanged = true;

        if ($lastHistory) {
            $priceDiffers = (float) $lastHistory->price !== (float) $currentPrice;
            $specialPriceDiffers = (float) $lastHistory->special_price !== (float) $currentSpecialPrice;

            if (! $priceDiffers && ! $specialPriceDiffers) {
                $hasChanged = false;
            }
        }

        if ($hasChanged) {
            $this->omnibusPriceRepository->create([
                'product_id' => $product->id,
                'price' => $currentPrice,
                'special_price' => $currentSpecialPrice,
            ]);
        }
    }
}
