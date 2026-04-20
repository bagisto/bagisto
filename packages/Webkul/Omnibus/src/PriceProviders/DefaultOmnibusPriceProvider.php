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
     * Record price snapshots for a batch of products of this provider's type across every active channel and currency.
     *
     * The optional callback fires once per product after its snapshots have been queued, enabling progress reporting.
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
        $originalCurrency = core()->getCurrentCurrency();

        try {
            foreach ($products as $product) {
                foreach ($enabledChannels as $channel) {
                    core()->setCurrentChannel($channel);

                    foreach ($channel->currencies as $currency) {
                        core()->setCurrentCurrency($currency);

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
            core()->setCurrentCurrency($originalCurrency);
        }

        foreach (array_chunk($insertRows, self::INSERT_CHUNK_SIZE) as $chunk) {
            $this->omnibusPriceRepository->getModel()->insert($chunk);
        }

        return $snapshotCount;
    }

    /**
     * Get the lowest price for a product within the configured lookback window prior to any active promo.
     */
    public function getLowestPrice(Product $product): ?float
    {
        $channelId = core()->getCurrentChannel()->id;
        $currencyCode = core()->getCurrentCurrencyCode();

        // Ceiling for "prices before the promo started". When special_price_from
        // is set, it bounds the lookback to snapshots taken before the discount
        // kicked in. When it's null (open-ended promo, or configurable parent
        // inheriting haveDiscount() from variants), use now() — exercising the
        // full 30-day window up to the present moment.
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
     * Render the Omnibus price block for a product.
     */
    public function getOmnibusPriceHtml(Product $product): string
    {
        if (! $product->getTypeInstance()->haveDiscount()) {
            return '';
        }

        $lowestPrice = $this->getLowestPrice($product);

        // No snapshot history yet — don't claim a "lowest price" we cannot
        // substantiate. The block appears once the scheduler has recorded at
        // least one snapshot for this product/channel/currency.
        if (
            is_null($lowestPrice)
            || $lowestPrice <= 0
        ) {
            return '';
        }

        $formattedPrice = core()->formatPrice($lowestPrice, core()->getCurrentCurrencyCode());

        return view('omnibus::shop.price-info.default', compact('formattedPrice'))->render();
    }

    /**
     * Get the ids of descendant products whose snapshots must be recorded alongside this one.
     *
     * Leaf types (simple, virtual, downloadable, booking) have no descendant
     * Products — downloadable links and booking slots are price modifiers on
     * the parent, not separate Product records. Composite types override this.
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
