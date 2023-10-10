<?php

namespace Webkul\Admin\Helpers\Reporting;

use Illuminate\Database\Eloquent\Collection;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\InvoiceRepository;

class Sale extends AbstractReporting
{
    /**
     * Create a helper instance.
     * 
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderItemRepository  $orderItemRepository
     * @param  \Webkul\Sales\Repositories\InvoiceRepository  $invoiceRepository
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderItemRepository $orderItemRepository,
        protected InvoiceRepository $invoiceRepository
    ) {
        parent::__construct();
    }

    /**
     * Retrieves total orders and their progress.
     * 
     * @param  \Carbon\Carbon|null  $startDate
     * @param  \Carbon\Carbon|null  $endDate
     * @return array
     */
    public function getTotalOrdersProgress($startDate = null, $endDate = null)
    {
        $previous = $this->orderRepository->getOrdersCountByDate(
            $startDate ? $this->yesterdayEndDate : $this->lastStartDate,
            $endDate ? $this->yesterdayStartDate : $this->lastEndDate
        );
    
        $current = $this->orderRepository->getOrdersCountByDate(
            $startDate ?? $this->startDate,
            $endDate ?? $this->endDate
        );

        return [
            'previous' => $previous,
            'current'  => $current,
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Retrieves total sales and their progress.
     * 
     * @param  \Carbon\Carbon|null  $startDate
     * @param  \Carbon\Carbon|null  $endDate
     * @return array
     */
    public function getTotalSalesProgress($startDate = null, $endDate = null): array
    {
        $previous = $this->orderRepository->getTotalSaleAmountByDate(
            $startDate ? $this->yesterdayEndDate : $this->lastStartDate,
            $endDate ? $this->yesterdayStartDate : $this->lastEndDate
        );
    
        $current = $this->orderRepository->getTotalSaleAmountByDate(
            $startDate ?? $this->startDate,
            $endDate ?? $this->endDate
        );

        return [
            'previous'        => $previous,
            'current'         => $current,
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Retrieves average sales and their progress.
     * 
     * @return array
     */
    public function getAvgSalesProgress(): array
    {
        return [
            'previous' => $previous = $this->orderRepository->getAvgSaleAmountByDate($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->orderRepository->getAvgSaleAmountByDate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Gets the total amount of pending invoices.
     * 
     * @return float
     */
    public function getTotalPendingInvoicesAmount(): float
    {
        return $this->invoiceRepository->getTotalPendingInvoicesAmount();
    }

    /**
     * Retrieves orders
     * 
     * @param  \Carbon\Carbon|null  $startDate
     * @param  \Carbon\Carbon|null  $endDate
     * @return array
     */
    public function getOrders($startDate = null, $endDate = null)
    {
        return $this->orderRepository->getOrdersByDate(
            $startDate ?? $this->startDate,
            $endDate ?? $this->endDate
        );
    }

    /**
     * Gets top-selling products.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopSellingProducts(): collection
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
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomersWithMostSales(): Collection
    {
        $customerWithMostSales = $this->orderRepository->getCustomersWithMostSalesByDate($this->startDate, $this->endDate);

        foreach ($customerWithMostSales as $order) {
            $order->formatted_total_base_grand_total = core()->formatBasePrice($order->total_base_grand_total);
        }

        return $customerWithMostSales;
    }

    /**
     * Returns sales over time for preparing chart.
     * 
     * @return array
     */
    public function getSalesOverTime(): array
    {
        $data = [];

        $sales = $this->orderRepository->getPerDayTotalSaleAmountByDate($this->startDate, $this->endDate);

        foreach ($this->getTimeInterval() as $interval) {
            $total = $sales->where('date', $interval['start']->format('Y-m-d'))->first();

            $data['label'][] = $interval['start']->format('d M');
            $data['total'][] = $total?->total ?? 0;
            $data['formatted_total'][] = core()->formatBasePrice($total?->total ?? 0);
        }

        return $data;
    }
}