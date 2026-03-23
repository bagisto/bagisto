<?php

namespace Webkul\Omnibus\Services;

use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Product\Contracts\Product;

class OmnibusPriceManager
{
    public function __construct(
        protected ChannelRepository $channelRepository,
        protected OmnibusPriceRepository $omnibusPriceRepository
    ) {
    }

    /**
     * Records the precise price of a product evaluated per active channel and currency.
     * Prevents duplicate sequential log entries.
     * Handles Configurable products recursively.
     *
     * @param  string|null  $recordedAt  Override date (defaults to right now)
     * @return int Number of snapshots created
     */
    public function recordPriceIfNeeded(Product $product, ?string $recordedAt = null): int
    {
        if (!core()->getConfigData('catalog.products.omnibus.is_enabled')) {
            return 0;
        }

        $channels = $this->channelRepository->all();
        $recordedAt = $recordedAt ?? now();
        $snapshotCount = 0;

        foreach ($channels as $channel) {
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

                $shouldSave = true;

                if ($latestSnapshot) {
                    if (round((float) $latestSnapshot->price, 4) === round((float) $price, 4)) {
                        $shouldSave = false;
                    }
                }

                if ($shouldSave) {
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
        }

        if ($product->type === 'configurable' && $product->variants) {
            foreach ($product->variants as $variant) {
                $snapshotCount += $this->recordPriceIfNeeded($variant, $recordedAt);
            }
        }

        return $snapshotCount;
    }

    /**
     * Garbage Collector: Usuwa rekordy starsze niż 35 dni
     */
    public function cleanOldRecords(): void
    {
        $this->omnibusPriceRepository->getModel()
            ->where('recorded_at', '<', now()->subDays(35))
            ->delete();
    }
}