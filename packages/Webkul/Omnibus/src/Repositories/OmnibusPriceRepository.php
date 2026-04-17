<?php

namespace Webkul\Omnibus\Repositories;

use Carbon\Carbon;
use Webkul\Core\Eloquent\Repository;
use Webkul\Omnibus\Contracts\OmnibusPrice;

class OmnibusPriceRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Omnibus\Contracts\OmnibusPrice';
    }

    /**
     * Get latest price history for a product and channel.
     *
     * @return OmnibusPrice|null
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
     * Get lowest active non-promotional or basic final price over the last 30 days.
     * Edge case: If product is less than 30 days old, it bounds smoothly via created_at intrinsically.
     */
    public function getLowestPrice(int $productId, int $channelId, string $currencyCode, $promoStartDate = null)
    {
        $startDate = Carbon::now()->subDays(30)->toDateTimeString();

        $query = $this->model
            ->where('product_id', $productId)
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
