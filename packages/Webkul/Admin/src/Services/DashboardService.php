<?php

namespace Webkul\Admin\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Models\Product;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;

class DashboardService
{
    /**
     * The starting date for a given period.
     */
    protected Carbon $startDate;

    /**
     * The ending date for a given period.
     */
    protected Carbon $endDate;

    /**
     * The starting date for the previous period.
     */
    protected Carbon $lastStartDate;

    /**
     * The ending date for the previous period.
     */
    protected Carbon $lastEndDate;

    /**
     * The start date for the previous day.
     */
    protected Carbon $yesterdayStartDate;

    /**
     * The end date for the previous day.
     */
    protected Carbon $yesterdayEndDate;

    /**
     * Create a service instance.
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderItemRepository $orderItemRepository,
        protected InvoiceRepository $invoiceRepository,
        protected CustomerRepository $customerRepository,
        protected ProductInventoryRepository $productInventoryRepository,
        protected ProductRepository $productRepository,
    ) {
        $this->setLastStartDate();
        $this->setLastEndDate();
        $this->yesterdayStartDate = now()->subDay()->startOfDay();
        $this->yesterdayEndDate = now()->subDay()->endOfDay();
    }

    /**
     * Gets statistics for various metrics.
     */
    public function getStatistics(): array
    {
        return [
            'total_customers' => $this->getTotalCustomers(),
            'total_orders' => $this->getTotalOrders(),
            'total_sales' => $this->getTotalSales(),
            'avg_sales' => $this->getAvgSales(),
            'total_unpaid_invoices' => $this->getTotalAmountOfPendingInvoices(),
            'top_selling_categories' => $this->getTopSellingCategories(),
            'top_selling_products' => $this->getTopSellingProducts(),
            'customer_with_most_sales' => $this->getCustomerWithMostSales(),
            'stock_threshold' => $this->getStockThreshold(),
            'sale_graph' => $this->generateSaleGraphData(),
            'today_details' => $this->getTodayDetails(),
        ];
    }

    /**
     * Get the start date.
     */
    public function getStartDate(): Carbon
    {
        return $this->startDate;
    }

    /**
     * Get the end date.
     */
    public function getEndDate(): Carbon
    {
        return $this->endDate;
    }

    /**
     * Set the start date or default to 30 days ago if not provided.
     */
    public function setStartDate(?Carbon $startDate = null): DashboardService
    {
        $this->startDate = $startDate ? $startDate->startOfDay() : now()->subDays(30)->startOfDay();

        return $this;
    }

    /**
     * Sets the end date to the provided date's end of day, or to the current 
     * date if not provided or if the provided date is in the future.
     */
    public function setEndDate(?Carbon $endDate = null): DashboardService
    {
        $this->endDate = ($endDate && $endDate->endOfDay() <= now()) ? $endDate->endOfDay() : now();

        return $this;
    }

    /**
     * Sets the start date for the last period.
     */
    private function setLastStartDate(): void
    {
        if (!isset($this->startDate)) {
            $this->setStartDate();
        }

        if (!isset($this->endDate)) {
            $this->setEndDate();
        }

        $this->lastStartDate = $this->startDate->clone()->subDays($this->startDate->diffInDays($this->endDate));
    }

    /**
     * Sets the end date for the last period.
     */
    private function setLastEndDate(): void
    {
        $this->lastEndDate = $this->startDate->clone();
    }

    /**
     * Calculate the percentage change between previous and current values.
     *
     * @param  float|int  $previous
     * @param  float|int  $current
     */
    private function getPercentageChange($previous, $current): float|int
    {
        if (!$previous) {
            return $current ? 100 : 0;
        }

        return ($current - $previous) / $previous * 100;
    }

    /**
     * Retrieves total customers and their progress.
     */
    private function getTotalCustomers($todayStartOfDay = null, $todayEndOfDay = null): array
    {
        if ($todayStartOfDay && $todayEndOfDay) {
            return [
                'previous' => $previous = $this->customerRepository->getCustomersCountByDate($this->yesterdayEndDate, $this->yesterdayStartDate),
                'current' => $current = $this->customerRepository->getCustomersCountByDate($todayStartOfDay, $todayEndOfDay),
                'progress' => $this->getPercentageChange($previous, $current)
            ];
        }

        return [
            'previous' => $previous = $this->customerRepository->getCustomersCountByDate($this->lastStartDate, $this->lastEndDate),
            'current' => $current = $this->customerRepository->getCustomersCountByDate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Retrieves total orders and their progress.
     */
    private function getTotalOrders($todayStartOfDay = null, $todayEndOfDay = null)
    {
        if ($todayStartOfDay && $todayEndOfDay) {
            return [
                'previous' => $previous = $this->orderRepository->getOrdersCountByDate($this->yesterdayEndDate, $this->yesterdayStartDate),
                'current' => $current = $this->orderRepository->getOrdersByDate($todayStartOfDay, $todayEndOfDay),
                'progress' => $this->getPercentageChange($previous, count($current))
            ];
        }

        return [
            'previous' => $previous = $this->orderRepository->getOrdersCountByDate($this->lastStartDate, $this->lastEndDate),
            'current' => $current = $this->orderRepository->getOrdersCountByDate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Retrieves total sales and their progress.
     */
    private function getTotalSales($todayStartOfDay = null, $todayEndOfDay = null): array
    {
        if ($todayStartOfDay && $todayEndOfDay) {
            return [
                'previous' => $previous = $this->orderRepository->calculateSaleAmountByDate($this->yesterdayEndDate, $this->yesterdayStartDate),
                'current' => $current = $this->orderRepository->calculateSaleAmountByDate($todayStartOfDay, $todayEndOfDay),
                'progress' => $this->getPercentageChange($previous, $current)
            ];
        }

        $getTotalSales = $this->orderRepository->calculateSaleAmountByDate($this->startDate, $this->endDate);

        return [
            'previous' => $previous = $this->orderRepository->calculateSaleAmountByDate($this->lastStartDate, $this->lastEndDate),
            'current' => $current = $getTotalSales,
            'formatted_total' => core()->formatBasePrice($getTotalSales),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Retrieves average sales and their progress.
     */
    private function getAvgSales(): array
    {
        return [
            'previous' => $previous = $this->orderRepository->calculateAvgSaleAmountByDate($this->lastStartDate, $this->lastEndDate),
            'current' => $current = $this->orderRepository->calculateAvgSaleAmountByDate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Gets the total amount of pending invoices.
     */
    private function getTotalAmountOfPendingInvoices(): float
    {
        return $this->invoiceRepository->getTotalAmountOfPendingInvoices();
    }

    /**
     * Gets the top-selling categories.
     */
    private function getTopSellingCategories(): SupportCollection
    {
        $orderItems = $this->orderItemRepository->getTopSellingOrderItemsByDate($this->startDate, $this->endDate);
        $categories = $this->getCategoryStats($orderItems)->collapse()->unique('category_id')->values();

        return $categories->map(fn (array $category) => (object) $category);
    }

    /**
     * Calculates category statistics from order items.
     */
    private function getCategoryStats(Collection $orderItems): SupportCollection
    {
        $productIds = $orderItems->pluck('product_id');
        $products = $this->productRepository->getModel()->whereIn('id', $productIds)->with('categories')->get();

        return $orderItems->map(function ($orderItem) use ($products) {
            $product = $products->firstWhere('id', $orderItem->product_id);

            return $this->getCategoryDetails($product, $orderItem);
        });
    }

    /**
     * Gets category details.
     */
    private function getCategoryDetails(Product $product, OrderItem $orderItem): SupportCollection
    {
        return $product->categories->map(function ($category) use ($orderItem) {
            return [
                'total_qty_invoiced' => $orderItem->total_qty_invoiced,
                'total_products' => $category->products->count(),
                'category_id' => $category->id,
                'name' => $category->translations->where('locale', app()->getLocale())->first()?->name,
            ];
        });
    }

    /**
     * Gets top-selling products.
     */
    private function getTopSellingProducts(): collection
    {
        $topSellingProducts = $this->orderItemRepository->getTopSellingProductsByDate($this->startDate, $this->endDate);

        foreach ($topSellingProducts as $orderItem) {
            $orderItem->formatted_total = core()->formatBasePrice($orderItem->total);
            $orderItem->formatted_price = core()->formatBasePrice($orderItem->price);
        }

        return $topSellingProducts;
    }

    /**
     * Gets customer with most sales.
     */
    private function getCustomerWithMostSales(): Collection
    {

        $customerWithMostSales = $this->orderRepository->getCustomerWithMostSalesByDate($this->startDate, $this->endDate);

        foreach ($customerWithMostSales as $order) {
            $order->formatted_total_base_grand_total = core()->formatBasePrice($order->total_base_grand_total);
        }

        return $customerWithMostSales;
    }

    /**
     * Gets stock threshold.
     */
    private function getStockThreshold(): Collection
    {
        return $this->productInventoryRepository->getStockThreshold();
    }

    /**
     * Generates sale graph data.
     */
    private function generateSaleGraphData(): array
    {
        $saleGraphData = [];

        foreach (core()->getTimeInterval($this->startDate, $this->endDate) as $interval) {
            $total = $this->orderRepository->calculateSaleAmountByDate($interval['start'], $interval['end']);

            $saleGraphData['label'][] = $interval['start']->format('d M');
            $saleGraphData['total'][] = $total;
            $saleGraphData['formatted_total'][] = core()->formatBasePrice($total);
        }

        return $saleGraphData;
    }

    private function getTodayDetails(): array
    {
        $todayStartOfDay = now()->today();
        $todayEndOfDay = now()->endOfDay();

        return [
            'today_customers' => $this->getTotalCustomers($todayStartOfDay, $todayEndOfDay),
            'today_sales'     => $this->getTotalSales($todayStartOfDay, $todayEndOfDay),
            'today_orders'    => $this->getTotalOrders($todayStartOfDay, $todayEndOfDay),
        ];
    }
}
