<?php

namespace Webkul\Omnibus\PriceProviders;

use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Omnibus\Contracts\OmnibusPriceProvider;
use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Product\Contracts\Product;
use Webkul\Product\Repositories\ProductRepository;

class DefaultOmnibusPriceProvider implements OmnibusPriceProvider
{
    /**
     * Create a new provider instance.
     */
    public function __construct(
        protected OmnibusPriceRepository $omnibusPriceRepository,
        protected ChannelRepository $channelRepository,
        protected ProductRepository $productRepository
    ) {}

    /**
     * Record a price snapshot for the product across every active channel and currency.
     */
    public function recordPrice(Product $product, ?string $recordedAt = null): int
    {
        if (! core()->getConfigData('catalog.products.omnibus.is_enabled')) {
            return 0;
        }

        $recordedAt = $recordedAt ?? now();
        $snapshotCount = 0;

        foreach ($this->channelRepository->all() as $channel) {
            core()->setCurrentChannel($channel);

            foreach ($channel->currencies as $currency) {
                core()->setCurrentCurrency($currency);

                $price = $product->getTypeInstance()->getMinimalPrice();

                if (is_null($price) || (float) $price === 0.0) {
                    continue;
                }

                $latestSnapshot = $this->omnibusPriceRepository->getLatestByProductIdAndChannel(
                    $product->id,
                    $channel->id,
                    $currency->code
                );

                if ($latestSnapshot && round((float) $latestSnapshot->price, 4) === round((float) $price, 4)) {
                    continue;
                }

                $this->omnibusPriceRepository->create([
                    'product_id' => $product->id,
                    'channel_id' => $channel->id,
                    'currency_code' => $currency->code,
                    'price' => $price,
                    'recorded_at' => $recordedAt,
                ]);
                $snapshotCount++;
            }
        }

        $childrenIds = $product->getTypeInstance()->getChildrenIds();

        if (! empty($childrenIds)) {
            foreach ($this->productRepository->findWhereIn('id', $childrenIds) as $child) {
                $snapshotCount += $this->recordPrice($child, $recordedAt);
            }
        }

        return $snapshotCount;
    }

    /**
     * Get the lowest price for a product in the 30 days prior to any active promo.
     */
    public function getLowestPrice(Product $product): ?float
    {
        if (! core()->getConfigData('catalog.products.omnibus.is_enabled')) {
            return null;
        }

        $channelId = core()->getCurrentChannel()->id;
        $currencyCode = core()->getCurrentCurrencyCode();
        $promoStartDate = $product->special_price_from;

        if (! $promoStartDate) {
            $latestSnapshot = $this->omnibusPriceRepository->getLatestByProductIdAndChannel(
                $product->id,
                $channelId,
                $currencyCode
            );
            $promoStartDate = $latestSnapshot ? $latestSnapshot->recorded_at : now();
        }

        return $this->omnibusPriceRepository->getLowestPrice(
            $this->getAggregatedProductIds($product),
            $channelId,
            $currencyCode,
            $promoStartDate
        );
    }

    /**
     * Get the lowest price formatted for display.
     */
    public function getLowestPriceFormatted(Product $product): ?string
    {
        $price = $this->getLowestPrice($product);

        if (is_null($price)) {
            return null;
        }

        return core()->formatPrice($price, core()->getCurrentCurrencyCode());
    }

    /**
     * Render the Omnibus price block for a product.
     */
    public function getOmnibusPriceHtml(Product $product): string
    {
        if (! core()->getConfigData('catalog.products.omnibus.is_enabled')) {
            return '';
        }

        if (! $product->getTypeInstance()->haveDiscount()) {
            return '';
        }

        $lowestPrice = $this->getLowestPrice($product) ?? $product->price;

        $formattedPrice = core()->formatPrice($lowestPrice, core()->getCurrentCurrencyCode());

        return view('omnibus::shop.omnibus-price-info', compact('formattedPrice'))->render();
    }

    /**
     * Get every product id whose snapshots contribute to this product's lowest price.
     */
    protected function getAggregatedProductIds(Product $product): array
    {
        return array_merge(
            [$product->id],
            $product->getTypeInstance()->getChildrenIds()
        );
    }
}
