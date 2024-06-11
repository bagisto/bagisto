<?php

namespace Webkul\Admin\Helpers\Reporting;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Checkout\Repositories\CartRepository;

class Cart extends AbstractReporting
{
    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected CartItemRepository $cartItemRepository
    ) {
        parent::__construct();
    }

    /**
     * Retrieves total carts and their progress.
     *
     * @return array
     */
    public function getTotalCartsProgress()
    {
        return [
            'previous' => $previous = $this->getTotalCarts($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalCarts($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves today carts and their progress.
     */
    public function getTodayCartsProgress(): array
    {
        return [
            'previous' => $previous = $this->getTotalCarts(now()->subDay()->startOfDay(), now()->subDay()->endOfDay()),
            'current'  => $current = $this->getTotalCarts(now()->today(), now()->endOfDay()),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total abandoned sales and their progress.
     *
     * @return array
     */
    public function getTotalAbandonedSalesProgress()
    {
        return [
            'previous'        => $previous = $this->getTotalAbandonedSales($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTotalAbandonedSales($this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total abandoned carts and their progress.
     *
     * @return array
     */
    public function getTotalAbandonedCartsProgress()
    {
        return [
            'previous' => $previous = $this->getTotalAbandonedCarts($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalAbandonedCarts($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total abandoned carts rate and their progress.
     *
     * @return array
     */
    public function getTotalAbandonedCartRateProgress()
    {
        return [
            'previous' => $previous = $this->getTotalAbandonedCartRate($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalAbandonedCartRate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total carts
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalCarts($startDate, $endDate): int
    {
        return $this->cartRepository
            ->resetModel()
            ->whereIn('channel_id', $this->channelIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Retrieves total abandoned carts
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalAbandonedCarts($startDate, $endDate): int
    {
        return $this->cartRepository
            ->resetModel()
            ->where('is_active', 1)
            ->whereIn('channel_id', $this->channelIds)
            ->whereBetween('created_at', [$startDate, $endDate->subDays(2)])
            ->count();
    }

    /**
     * Retrieves total abandoned cart rate
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalAbandonedCartRate($startDate, $endDate): float
    {
        $totalCarts = $this->getTotalCarts($startDate, $endDate);

        if (! $totalCarts) {
            return 0;
        }

        return ($this->getTotalAbandonedCarts($startDate, $endDate) * 100) / $totalCarts;
    }

    /**
     * Retrieves total abandoned sales
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalAbandonedSales($startDate, $endDate): int
    {
        return $this->cartRepository
            ->resetModel()
            ->where('is_active', 1)
            ->whereIn('channel_id', $this->channelIds)
            ->whereBetween('created_at', [$startDate, $endDate->subDays(2)])
            ->sum('base_grand_total');
    }

    /**
     * Retrieves abandoned cart products
     *
     * @param  int  $limit
     */
    public function getAbandonedCartProducts($limit = null): Collection
    {
        return $this->cartItemRepository
            ->resetModel()
            ->leftJoin('cart', 'cart_items.cart_id', '=', 'cart.id')
            ->select('product_id as id', 'name')
            ->addSelect(DB::raw('COUNT(*) as count'))
            ->where('is_active', 1)
            ->whereIn('cart.channel_id', $this->channelIds)
            ->whereBetween('cart.created_at', [$this->startDate, $this->endDate->subDays(2)])
            ->groupBy('product_id')
            ->limit($limit)
            ->orderByDesc('count')
            ->get();
    }

    /**
     * Retrieves total abandoned cart products
     */
    public function getTotalAbandonedCartProducts(): int
    {
        return $this->cartItemRepository
            ->resetModel()
            ->distinct('product_id')
            ->leftJoin('cart', 'cart_items.cart_id', '=', 'cart.id')
            ->where('is_active', 1)
            ->whereIn('cart.channel_id', $this->channelIds)
            ->whereBetween('cart.created_at', [$this->startDate, $this->endDate->subDays(2)])
            ->count();
    }

    /**
     * Retrieves total unique cart users
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getTotalUniqueCartsUsers($startDate, $endDate): int
    {
        return $this->cartRepository
            ->resetModel()
            ->groupBy(DB::raw('CONCAT(customer_email, "-", customer_id)'))
            ->whereIn('cart.channel_id', $this->channelIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->count();
    }
}
