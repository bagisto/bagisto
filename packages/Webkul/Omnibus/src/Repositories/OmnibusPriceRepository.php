<?php

namespace Webkul\Omnibus\Repositories;

use Carbon\Carbon;
use Webkul\Core\Eloquent\Repository;

class OmnibusPriceRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Omnibus\Contracts\OmnibusPrice';
    }

    /**
     * Get the latest price snapshot for a product within a channel and currency.
     */
    public function getLatestByProductIdAndChannel(int $productId, int $channelId, string $currencyCode)
    {
        return $this->model
            ->where('product_id', $productId)
            ->where('channel_id', $channelId)
            ->where('currency_code', $currencyCode)
            ->orderBy('recorded_at', 'desc')
            ->first();
    }

    /**
     * Get the lowest snapshot price across the given products over the last 30 days before the promo start.
     */
    public function getLowestPrice(array $productIds, int $channelId, string $currencyCode, $promoStartDate = null): ?float
    {
        $startDate = Carbon::now()->subDays(30)->toDateTimeString();

        $query = $this->model
            ->whereIn('product_id', $productIds)
            ->where('channel_id', $channelId)
            ->where('currency_code', $currencyCode)
            ->where('recorded_at', '>=', $startDate);

        if ($promoStartDate) {
            $query->where('recorded_at', '<', Carbon::parse($promoStartDate)->toDateTimeString());
        }

        $minPrice = $query->min('price');

        return $minPrice !== null ? (float) $minPrice : null;
    }
}
