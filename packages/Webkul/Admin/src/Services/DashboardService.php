<?php

namespace Webkul\Admin\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;

class DashboardService
{
    protected Carbon $startDate;
    protected Carbon $endDate;

    protected Carbon $lastStartDate;
    protected Carbon $lastEndDate;

    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderItemRepository $orderItemRepository,
        protected InvoiceRepository $invoiceRepository,
        protected CustomerRepository $customerRepository,
        protected ProductInventoryRepository $productInventoryRepository,
        protected ProductRepository $productRepository,
    )
    {
        $this->setLastStartDate();
        $this->setLastEndDate();
    }

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
            'sale_graph' => $this->generateSaleGraphData()
        ];
    }

    public function getStartDate(): Carbon
    {
        return $this->startDate;
    }

    public function getEndDate(): Carbon
    {
        return $this->endDate;
    }

    public function setStartDate(?Carbon $startDate = null): DashboardService
    {
        $this->startDate = $startDate ? $startDate->startOfDay() : now()->subDays(30)->startOfDay();

        return $this;
    }

    public function setEndDate(?Carbon $endDate = null): DashboardService
    {
        $this->endDate = ($endDate && $endDate->endOfDay() <= now()) ? $endDate->endOfDay() : now();

        return $this;
    }

    private function setLastStartDate(): void
    {
        if (! isset($this->startDate)) {
            $this->setStartDate();
        }

        if (! isset($this->endDate)) {
            $this->setEndDate();
        }

        $this->lastStartDate = $this->startDate->clone()->subDays($this->startDate->diffInDays($this->endDate));
    }

    private function setLastEndDate(): void
    {
        $this->lastEndDate = $this->startDate->clone();
    }

    private function getPercentageChange($previous, $current): float|int
    {
        if (! $previous) {
            return $current ? 100 : 0;
        }

        return ($current - $previous) / $previous * 100;
    }

    private function getTotalCustomers(): array
    {
        return [
            'previous' => $previous = $this->customerRepository->getCustomersCountByDate($this->lastStartDate, $this->lastEndDate),
            'current' => $current = $this->customerRepository->getCustomersCountByDate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    private function getTotalOrders(): array
    {
        return [
            'previous' => $previous = $this->orderRepository->getOrdersCountByDate($this->lastStartDate, $this->lastEndDate),
            'current' => $current = $this->orderRepository->getOrdersCountByDate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    private function getTotalSales(): array
    {
        return [
            'previous' => $previous = $this->orderRepository->calculateSaleAmountByDate($this->lastStartDate, $this->lastEndDate),
            'current' => $current = $this->orderRepository->calculateSaleAmountByDate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    private function getAvgSales(): array
    {
        return [
            'previous' => $previous = $this->orderRepository->calculateAvgSaleAmountByDate($this->lastStartDate, $this->lastEndDate),
            'current' => $current = $this->orderRepository->calculateAvgSaleAmountByDate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    private function getTotalAmountOfPendingInvoices(): float
    {
        return $this->invoiceRepository->getTotalAmountOfPendingInvoices();
    }

    private function getTopSellingCategories(): SupportCollection
    {
        $orderItems = $this->orderItemRepository->getTopSellingOrderItemsByDate($this->startDate, $this->endDate);
        $categories = $this->getCategoryStats($orderItems)->collapse()->unique('category_id')->values();

        return $categories->map(fn (array $category) => (object) $category);
    }

    private function getCategoryStats(Collection $orderItems): SupportCollection
    {
        return $orderItems->map(function ($orderItem) {
            $product = $this->productRepository->find($orderItem->product_id);

            return $this->getCategoryDetails($product, $orderItem);
        });
    }

    private function getCategoryDetails($product, $orderItem): SupportCollection
    {
        return $product->categories->map(function ($category) use ($orderItem) {
            return [
                'total_qty_invoiced' => $orderItem->total_qty_invoiced,
                'total_products' => $category->products->count(),
                'category_id' => $category->id,
                'name' => $category->translations->where('locale', app()->getLocale())->first()->name,
            ];
        });
    }

    private function getTopSellingProducts(): Collection
    {
        return $this->orderItemRepository->getTopSellingProductsByDate($this->startDate, $this->endDate);
    }

    private function getCustomerWithMostSales(): Collection
    {
        return $this->orderRepository->getCustomerWithMostSalesByDate($this->startDate, $this->endDate);
    }

    private function getStockThreshold(): Collection
    {
        return $this->productInventoryRepository->getStockThreshold();
    }

    /**
     * @return array
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
}

