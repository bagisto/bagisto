<?php

namespace Webkul\Omnibus\Helpers;

use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Product\Contracts\Product;

class OmnibusHelper
{
    public function __construct(
        protected OmnibusPriceRepository $omnibusPriceRepository
    ) {
    }

    public function getLowestPrice(Product $product): ?float
    {
        if (!core()->getConfigData('catalog.products.omnibus.is_enabled')) {
            return null;
        }

        $channelId = core()->getCurrentChannel()->id;
        $currencyCode = core()->getCurrentCurrencyCode();
        $promoStartDate = $product->special_price_from;

        if (!$promoStartDate) {
            $latestSnapshot = $this->omnibusPriceRepository->getLatestByProductIdAndChannel(
                $product->id,
                $channelId,
                $currencyCode
            );
            $promoStartDate = $latestSnapshot ? $latestSnapshot->recorded_at : now();
        }

        if ($product->type === 'configurable') {
            $variantIds = $product->variants()->pluck('id')->toArray();

            if (empty($variantIds)) {
                return null;
            }

            return (float) $this->omnibusPriceRepository->getModel()
                ->whereIn('product_id', $variantIds)
                ->where('channel_id', $channelId)
                ->where('currency_code', $currencyCode)
                ->where('recorded_at', '>=', now()->subDays(30))
                ->where('recorded_at', '<', $promoStartDate)
                ->min('price');
        }

        return $this->omnibusPriceRepository->getLowestPrice(
            $product->id,
            $channelId,
            $currencyCode,
            $promoStartDate
        );
    }

    /**
     * Zwraca sformatowaną najniższą cenę dango produktu dla JS (Vue).
     *
     * @param Product $product
     * @return string
     */
    public function getLowestPriceFormatted(Product $product): ?string
    {
        $price = $this->getLowestPrice($product);

        // Jeśli brak ceny lub brak promocji (według logiki getLowestPrice)
        if (is_null($price)) {
            return null;
        }

        return core()->formatPrice($price, core()->getCurrentCurrencyCode());
    }

    public function getOmnibusPriceHtml(Product $product): string
    {
        if (!core()->getConfigData('catalog.products.omnibus.is_enabled')) {
            return '';
        }

        if (!$product->getTypeInstance()->haveDiscount()) {
            return '';
        }

        $lowestPrice = $this->getLowestPrice($product);

        if (is_null($lowestPrice)) {
            $lowestPrice = $product->price;
        }

        $formattedPrice = core()->formatPrice($lowestPrice, core()->getCurrentCurrencyCode());

        return view('omnibus::shop.omnibus-price-info', compact('formattedPrice'))->render();
    }
}