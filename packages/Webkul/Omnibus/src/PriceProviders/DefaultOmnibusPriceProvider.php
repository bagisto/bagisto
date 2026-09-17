<?php

namespace Webkul\Omnibus\PriceProviders;

use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Omnibus\Contracts\OmnibusPriceProvider;
use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Product\Contracts\Product;

class DefaultOmnibusPriceProvider implements OmnibusPriceProvider
{
    /**
     * Number of rows per bulk INSERT statement.
     * Keeps Postgres parameter count under its 65 535 limit.
     */
    protected const INSERT_CHUNK_SIZE = 500;

    /**
     * Create a new provider instance.
     */
    public function __construct(
        protected OmnibusPriceRepository $omnibusPriceRepository,
        protected ChannelRepository $channelRepository
    ) {}

    /**
     * Record a price snapshot for a single product across every active channel and currency.
     */
    public function recordPrice(Product $product, ?string $recordedAt = null): int
    {
        return $this->recordBulkPrice([$product], $recordedAt);
    }

    /**
     * Record price snapshots for a batch of products across every active channel and currency,
     * calling the optional callback once each product's snapshots are queued.
     */
    public function recordBulkPrice(array $products, ?string $recordedAt = null, ?callable $afterEach = null): int
    {
        if (empty($products)) {
            return 0;
        }

        $recordedAt = $recordedAt ?? now();
        $snapshotCount = 0;
        $insertRows = [];

        $latestPriceMap = $this->fetchLatestPriceMap(array_map(fn ($product) => $product->id, $products));

        $enabledChannels = $this->channelRepository->all()
            ->filter(fn ($channel) => core()->getConfigData('catalog.products.omnibus.is_enabled', $channel->code));

        $originalChannel = core()->getCurrentChannel();
        $originalCurrencyCode = core()->getCurrentCurrencyCode();

        try {
            foreach ($products as $product) {
                foreach ($enabledChannels as $channel) {
                    core()->setCurrentChannel($channel);

                    foreach ($channel->currencies as $currency) {
                        core()->setCurrentCurrency($currency->code);

                        $price = $product->getTypeInstance()->getMinimalPrice();

                        if (
                            is_null($price)
                            || (float) $price === 0.0
                        ) {
                            continue;
                        }

                        $key = $product->id.':'.$channel->id.':'.$currency->code;
                        $latestPrice = $latestPriceMap[$key] ?? null;

                        if (
                            $latestPrice !== null
                            && round((float) $latestPrice, 4) === round((float) $price, 4)
                        ) {
                            continue;
                        }

                        $insertRows[] = [
                            'product_id' => $product->id,
                            'channel_id' => $channel->id,
                            'currency_code' => $currency->code,
                            'price' => $price,
                            'recorded_at' => $recordedAt,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        $latestPriceMap[$key] = $price;
                        $snapshotCount++;
                    }
                }

                if ($afterEach) {
                    $afterEach($product);
                }
            }
        } finally {
            core()->setCurrentChannel($originalChannel);
            core()->setCurrentCurrency($originalCurrencyCode);
        }

        foreach (array_chunk($insertRows, self::INSERT_CHUNK_SIZE) as $chunk) {
            $this->omnibusPriceRepository->getModel()->insert($chunk);
        }

        return $snapshotCount;
    }

    /**
     * Get the lowest price for a product within the lookback window before its promo started,
     * or before now when the promo has no start date.
     */
    public function getLowestPrice(Product $product): ?float
    {
        $channelId = core()->getCurrentChannel()->id;
        $currencyCode = core()->getCurrentCurrencyCode();

        $promoStartDate = $product->special_price_from ?: now();

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
     * Render the Omnibus price block for a discounted product, or nothing until a snapshot
     * backs its lowest price.
     */
    public function getOmnibusPriceHtml(Product $product): string
    {
        if (! $product->getTypeInstance()->haveDiscount()) {
            return '';
        }

        $lowestPrice = $this->getLowestPrice($product);

        if (
            is_null($lowestPrice)
            || $lowestPrice <= 0
        ) {
            return '';
        }

        $formattedPrice = core()->formatPrice($lowestPrice, core()->getCurrentCurrencyCode());

        return view('shop::products.omnibus.default', compact('formattedPrice'))->render();
    }

    /**
     * Get the ids of descendant products whose snapshots must be recorded alongside this one,
     * none for a leaf type; composite types override this.
     */
    public function getDescendantProductIds(Product $product): array
    {
        return [];
    }

    /**
     * Get every product id whose snapshots contribute to this product's lowest price.
     */
    protected function getAggregatedProductIds(Product $product): array
    {
        return array_merge([$product->id], $this->getDescendantProductIds($product));
    }

    /**
     * Fetch the most recent price per (product, channel, currency) tuple for the given products in one query.
     *
     * @return array<string, string> keyed by "{productId}:{channelId}:{currencyCode}"
     */
    protected function fetchLatestPriceMap(array $productIds): array
    {
        if (empty($productIds)) {
            return [];
        }

        $table = $this->omnibusPriceRepository->getModel()->getTable();

        $latestTimestamps = $this->omnibusPriceRepository->getModel()
            ->newQuery()
            ->whereIn('product_id', $productIds)
            ->groupBy('product_id', 'channel_id', 'currency_code')
            ->select('product_id', 'channel_id', 'currency_code')
            ->selectRaw('MAX(recorded_at) as max_recorded_at');

        $rows = $this->omnibusPriceRepository->getModel()
            ->newQuery()
            ->from($table.' as snapshots')
            ->joinSub($latestTimestamps, 'latest', function ($join) {
                $join->on('snapshots.product_id', '=', 'latest.product_id')
                    ->on('snapshots.channel_id', '=', 'latest.channel_id')
                    ->on('snapshots.currency_code', '=', 'latest.currency_code')
                    ->on('snapshots.recorded_at', '=', 'latest.max_recorded_at');
            })
            ->select('snapshots.product_id', 'snapshots.channel_id', 'snapshots.currency_code', 'snapshots.price')
            ->get();

        $map = [];

        foreach ($rows as $row) {
            $map[$row->product_id.':'.$row->channel_id.':'.$row->currency_code] = $row->price;
        }

        return $map;
    }
}
