<?php

namespace Webkul\Admin\Helpers\Reporting;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Marketing\Repositories\SearchTermRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Sales\Repositories\OrderItemRepository;

class Product extends AbstractReporting
{
    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductInventoryRepository $productInventoryRepository,
        protected WishlistRepository $wishlistRepository,
        protected ProductReviewRepository $reviewRepository,
        protected OrderItemRepository $orderItemRepository,
        protected SearchTermRepository $searchTermRepository
    ) {
        parent::__construct();
    }

    /**
     * Retrieve total sold quantities and their progress.
     *
     * @return array
     */
    public function getTotalSoldQuantitiesProgress()
    {
        return [
            'previous' => $previous = $this->getTotalSoldQuantities($this->lastStartDate, $this->lastEndDate),
            'current' => $current = $this->getTotalSoldQuantities($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Return previous sold quantities over time.
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousTotalSoldQuantitiesOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalSoldQuantitiesOverTime($this->lastStartDate, $this->lastEndDate, $period);
    }

    /**
     * Return current sold quantities over time.
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentTotalSoldQuantitiesOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalSoldQuantitiesOverTime($this->startDate, $this->endDate, $period);
    }

    /**
     * Retrieve total sold quantities for the given date range.
     *
     * @param  Carbon  $startDate
     * @param  Carbon  $endDate
     */
    public function getTotalSoldQuantities($startDate, $endDate): int
    {
        return $this->orderItemRepository
            ->resetModel()
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.channel_id', $this->channelIds)
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->value(DB::raw('SUM(qty_invoiced - qty_refunded)')) ?? 0;
    }

    /**
     * Retrieve total products added to wishlist and their progress.
     *
     * @return array
     */
    public function getTotalProductsAddedToWishlistProgress()
    {
        return [
            'previous' => $previous = $this->getTotalProductsAddedToWishlist($this->lastStartDate, $this->lastEndDate),
            'current' => $current = $this->getTotalProductsAddedToWishlist($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Return previous products added to wishlist over time.
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousTotalProductsAddedToWishlistOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalProductsAddedToWishlistOverTime($this->lastStartDate, $this->lastEndDate, $period);
    }

    /**
     * Return current products added to wishlist over time.
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentTotalProductsAddedToWishlistOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalProductsAddedToWishlistOverTime($this->startDate, $this->endDate, $period);
    }

    /**
     * Retrieve total products added to wishlist for the given date range.
     *
     * @param  Carbon  $startDate
     * @param  Carbon  $endDate
     */
    public function getTotalProductsAddedToWishlist($startDate, $endDate): int
    {
        return $this->wishlistRepository
            ->resetModel()
            ->whereIn('channel_id', $this->channelIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Retrieve total reviews and their progress.
     */
    public function getTotalReviewsProgress(): array
    {
        return [
            'previous' => $previous = $this->getTotalReviews($this->lastStartDate, $this->lastEndDate),
            'current' => $current = $this->getTotalReviews($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieve total reviews for the given date range.
     *
     * @param  Carbon  $startDate
     * @param  Carbon  $endDate
     */
    public function getTotalReviews($startDate, $endDate): int
    {
        return $this->reviewRepository
            ->resetModel()
            ->leftJoin('product_channels', 'product_reviews.product_id', '=', 'product_channels.product_id')
            ->where('status', 'approved')
            ->whereIn('channel_id', $this->channelIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Retrieve products with stock below threshold, grouped by product and inventory source.
     *
     * @param  int  $limit
     */
    public function getStockThresholdProducts($limit = null): EloquentCollection
    {
        $prefix = DB::getTablePrefix();

        return $this->productInventoryRepository
            ->resetModel()
            ->with(['product', 'product.attribute_family', 'product.attribute_values', 'product.images'])
            ->leftJoin('product_channels', 'product_inventories.product_id', '=', 'product_channels.product_id')
            ->select(
                DB::raw("MIN({$prefix}product_inventories.id) as id"),
                'product_inventories.product_id',
                'product_inventories.inventory_source_id',
                DB::raw('SUM(qty) as total_qty')
            )
            ->whereIn('channel_id', $this->channelIds)
            ->groupBy('product_inventories.product_id', 'product_inventories.inventory_source_id')
            ->orderBy('total_qty', 'ASC')
            ->limit($limit)
            ->get();
    }

    /**
     * Retrieve top-selling products ranked by revenue.
     *
     * @param  int  $limit
     */
    public function getTopSellingProductsByRevenue($limit = null): Collection
    {
        $prefix = DB::getTablePrefix();

        $items = $this->orderItemRepository
            ->resetModel()
            ->with(['product', 'product.attribute_family', 'product.attribute_values', 'product.images'])
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->addSelect(
                DB::raw("MIN({$prefix}order_items.id) as id"),
                'order_items.product_id',
                'order_items.product_type',
                'order_items.name',
                'order_items.price',
                DB::raw('SUM(base_total_invoiced - base_amount_refunded) as revenue')
            )
            ->whereNull('parent_id')
            ->whereIn('channel_id', $this->channelIds)
            ->whereBetween('order_items.created_at', [$this->startDate, $this->endDate])
            ->having(DB::raw('SUM(base_total_invoiced - base_amount_refunded)'), '>', 0)
            ->groupBy('order_items.product_id', 'order_items.product_type', 'order_items.name', 'order_items.price')
            ->orderBy('revenue', 'DESC')
            ->limit($limit)
            ->get();

        $items = $items->map(function ($item) {
            return [
                'id' => $item->product_id,
                'name' => $item->name,
                'price' => $item->product?->price,
                'formatted_price' => core()->formatBasePrice($item->price),
                'revenue' => $item->revenue,
                'formatted_revenue' => core()->formatBasePrice($item->revenue),
                'images' => $item->product?->images,
            ];
        });

        return $items;
    }

    /**
     * Retrieve top-selling products ranked by quantity sold.
     *
     * @param  int  $limit
     */
    public function getTopSellingProductsByQuantity($limit = null): Collection
    {
        $prefix = DB::getTablePrefix();

        $items = $this->orderItemRepository
            ->resetModel()
            ->with(['product', 'product.attribute_family', 'product.attribute_values', 'product.images'])
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->addSelect(
                DB::raw("MIN({$prefix}order_items.id) as id"),
                'order_items.product_id',
                'order_items.product_type',
                'order_items.name',
                'order_items.price',
                DB::raw('SUM(qty_invoiced - qty_refunded) as total_qty_ordered')
            )
            ->whereNull('parent_id')
            ->whereIn('channel_id', $this->channelIds)
            ->whereBetween('order_items.created_at', [$this->startDate, $this->endDate])
            ->having(DB::raw('SUM(qty_invoiced - qty_refunded)'), '>', 0)
            ->groupBy('order_items.product_id', 'order_items.product_type', 'order_items.name', 'order_items.price')
            ->orderBy('total_qty_ordered', 'DESC')
            ->limit($limit)
            ->get();

        $items = $items->map(function ($item) {
            return [
                'id' => $item->product_id,
                'name' => $item->name,
                'price' => $item->product?->price,
                'formatted_price' => core()->formatBasePrice($item->price),
                'total_qty_ordered' => $item->total_qty_ordered,
                'images' => $item->product?->images,
            ];
        });

        return $items;
    }

    /**
     * Retrieve products with the most approved reviews.
     *
     * @param  int  $limit
     */
    public function getProductsWithMostReviews($limit = null): EloquentCollection
    {
        $prefix = DB::getTablePrefix();

        $products = $this->reviewRepository
            ->resetModel()
            ->leftJoin('product_channels', 'product_reviews.product_id', '=', 'product_channels.product_id')
            ->addSelect(
                DB::raw("MIN({$prefix}product_reviews.id) as id"),
                'product_reviews.product_id',
                DB::raw('COUNT(*) as reviews')
            )
            ->whereIn('channel_id', $this->channelIds)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', 'approved')
            ->groupBy('product_reviews.product_id')
            ->orderByDesc('reviews')
            ->limit($limit)
            ->get();

        $products->map(function ($product) {
            $product->product_name = $product->product->name;
        });

        return $products;
    }

    /**
     * Retrieve the most recent search terms.
     *
     * @param  int  $limit
     */
    public function getLastSearchTerms($limit = null): EloquentCollection
    {
        return $this->searchTermRepository
            ->resetModel()
            ->whereIn('channel_id', $this->channelIds)
            ->whereBetween('updated_at', [$this->startDate, $this->endDate])
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Retrieve the most used search terms.
     *
     * @param  int  $limit
     */
    public function getTopSearchTerms($limit = null): EloquentCollection
    {
        return $this->searchTermRepository
            ->resetModel()
            ->whereIn('channel_id', $this->channelIds)
            ->orderByDesc('uses')
            ->limit($limit)
            ->get();
    }

    /**
     * Return sold quantities over time for the given date range.
     *
     * @param  Carbon  $startDate
     * @param  Carbon  $endDate
     * @param  string  $period
     */
    public function getTotalSoldQuantitiesOverTime($startDate, $endDate, $period = 'auto'): array
    {
        $tablePrefix = DB::getTablePrefix();

        $config = $this->getTimeInterval($startDate, $endDate, $period);

        $groupColumn = str_replace('created_at', "{$tablePrefix}order_items.created_at", $config['group_column']);

        $results = $this->orderItemRepository
            ->resetModel()
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                DB::raw("$groupColumn AS date"),
                DB::raw('COUNT(*) AS total')
            )
            ->whereIn('channel_id', $this->channelIds)
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get();

        $stats = [];

        foreach ($config['intervals'] as $interval) {
            $total = $results->where('date', $interval['filter'])->first();

            $stats[] = [
                'label' => $interval['start'],
                'total' => $total?->total ?? 0,
            ];
        }

        return $stats;
    }

    /**
     * Return products added to wishlist over time for the given date range.
     *
     * @param  Carbon  $startDate
     * @param  Carbon  $endDate
     * @param  string  $period
     */
    public function getTotalProductsAddedToWishlistOverTime($startDate, $endDate, $period = 'auto'): array
    {
        $config = $this->getTimeInterval($startDate, $endDate, $period);

        $groupColumn = $config['group_column'];

        $results = $this->wishlistRepository
            ->resetModel()
            ->select(
                DB::raw("$groupColumn AS date"),
                DB::raw('COUNT(*) AS total')
            )
            ->whereIn('channel_id', $this->channelIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get();

        $stats = [];

        foreach ($config['intervals'] as $interval) {
            $total = $results->where('date', $interval['filter'])->first();

            $stats[] = [
                'label' => $interval['start'],
                'total' => $total?->total ?? 0,
            ];
        }

        return $stats;
    }
}
