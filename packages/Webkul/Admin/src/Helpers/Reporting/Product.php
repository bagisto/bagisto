<?php

namespace Webkul\Admin\Helpers\Reporting;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Sales\Repositories\OrderItemRepository;

class Product extends AbstractReporting
{
    /**
     * Create a helper instance.
     * 
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryRepository  $productInventoryRepository
     * @param  \Webkul\Customer\Repositories\WishlistRepository  $wishlistRepository
     * @param  \Webkul\Product\Repositories\ProductReviewRepository  $reviewRepository
     * @param  \Webkul\Sales\Repositories\OrderItemRepository  $orderItemRepository
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductInventoryRepository $productInventoryRepository,
        protected WishlistRepository $wishlistRepository,
        protected ProductReviewRepository $reviewRepository,
        protected OrderItemRepository $orderItemRepository,
    )
    {
        parent::__construct();
    }

    /**
     * Retrieves total sold quantities and their progress.
     * 
     * @return array
     */
    public function getTotalSoldQuantitiesProgress()
    {
        return [
            'previous' => $previous = $this->getTotalSoldQuantities($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalSoldQuantities($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Returns previous sold quantities over time
     * 
     * @return array
     */
    public function getPreviousTotalSoldQuantitiesOverTime(): array
    {
        return $this->getTotalSoldQuantitiesOverTime($this->lastStartDate, $this->lastEndDate);
    }

    /**
     * Returns current sold quantities over time
     * 
     * @return array
     */
    public function getCurrentTotalSoldQuantitiesOverTime(): array
    {
        return $this->getTotalSoldQuantitiesOverTime($this->startDate, $this->endDate);
    }

    /**
     * Retrieves total sold quantities.
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return int
     */
    public function getTotalSoldQuantities($startDate, $endDate): int
    {
        return $this->orderItemRepository
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('qty_ordered');
    }

    /**
     * Retrieves total products added to wishlist and their progress.
     * 
     * @return array
     */
    public function getTotalProductsAddedToWishlistProgress()
    {
        return [
            'previous' => $previous = $this->getTotalProductsAddedToWishlist($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalProductsAddedToWishlist($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Returns previous products added to wishlist over time
     * 
     * @return array
     */
    public function getPreviousTotalProductsAddedToWishlistOverTime(): array
    {
        return $this->getTotalProductsAddedToWishlistOverTime($this->lastStartDate, $this->lastEndDate);
    }

    /**
     * Returns current products added to wishlist over time
     * 
     * @return array
     */
    public function getCurrentTotalProductsAddedToWishlistOverTime(): array
    {
        return $this->getTotalProductsAddedToWishlistOverTime($this->startDate, $this->endDate);
    }

    /**
     * Retrieves total products added to wishlist.
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return int
     */
    public function getTotalProductsAddedToWishlist($startDate, $endDate): int
    {
        return $this->wishlistRepository
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Retrieves total reviews and their progress.
     * 
     * @return array
     */
    public function getTotalReviewsProgress(): array
    {
        return [
            'previous' => $previous = $this->getTotalReviews($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalReviews($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Retrieves total reviews by date
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return integer
     */
    public function getTotalReviews($startDate, $endDate): int
    {
        return $this->reviewRepository
            ->where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Gets stock threshold.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStockThreshold(): Collection
    {
        return $this->productInventoryRepository->getStockThreshold();
    }

    /**
     * Gets top-selling products by revenue.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopSellingProductsByRevenue(): collection
    {
        $products = $this->orderItemRepository
            ->with(['product', 'product.images'])
            ->addSelect('*', DB::raw('SUM(base_total_invoiced - base_discount_refunded) as revenue'))
            ->whereNull('parent_id')
            ->whereBetween('order_items.created_at', [$this->startDate, $this->endDate])
            ->groupBy('product_id')
            ->orderBy('revenue', 'DESC')
            ->limit(5)
            ->get();
        

        $products->map(function($product) {
            $product->formatted_revenue = core()->formatBasePrice($product->revenue);

            $product->formatted_price = core()->formatBasePrice($product->price);
        });

        return $products;
    }

    /**
     * Gets top-selling products by quantity.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopSellingProductsByQuantity(): collection
    {
        $products = $this->orderItemRepository
            ->with(['product', 'product.images'])
            ->addSelect('*', DB::raw('SUM(qty_ordered) as total_qty_ordered'))
            ->whereNull('parent_id')
            ->whereBetween('order_items.created_at', [$this->startDate, $this->endDate])
            ->groupBy('product_id')
            ->orderBy('total_qty_ordered', 'DESC')
            ->limit(5)
            ->get();

        return $products;
    }

    /**
     * Gets products with most orders.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProductsWithMostReviews(): Collection
    {
        $tablePrefix = DB::getTablePrefix();

        $products = $this->reviewRepository
            ->addSelect(
                'product_id',
                DB::raw('COUNT(*) as reviews')
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', 'approved')
            ->groupBy('product_id')
            ->orderByDesc('reviews')
            ->limit(5)
            ->get();

        $products->map(function ($product) {
            $product->product_name = $product->product->name;
        });

        return $products;
    }

    /**
     * Returns sold quantities over time
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @return array
     */
    public function getTotalSoldQuantitiesOverTime($startDate, $endDate, $period = 'auto'): array
    {
        $config = $this->getTimeInterval($startDate, $endDate, $period);

        $groupColumn = $config['group_column'];

        $results = $this->orderItemRepository
            ->select(
                DB::raw("$groupColumn AS date"),
                DB::raw('COUNT(*) AS total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw($groupColumn))
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
     * Returns products added to wishlist over time
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @return array
     */
    public function getTotalProductsAddedToWishlistOverTime($startDate, $endDate, $period = 'auto'): array
    {
        $config = $this->getTimeInterval($startDate, $endDate, $period);

        $groupColumn = $config['group_column'];

        $results = $this->wishlistRepository
            ->select(
                DB::raw("$groupColumn AS date"),
                DB::raw('COUNT(*) AS total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw($groupColumn))
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