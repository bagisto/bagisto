<?php

namespace Webkul\Admin\Helpers\Reporting;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\RefundRepository;

class Sale extends AbstractReporting
{
    /**
     * Create a helper instance.
     * 
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderItemRepository  $orderItemRepository
     * @param  \Webkul\Sales\Repositories\InvoiceRepository  $invoiceRepository
     * @param  \Webkul\Sales\Repositories\RefundRepository  $refundRepository
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderItemRepository $orderItemRepository,
        protected InvoiceRepository $invoiceRepository,
        protected RefundRepository $refundRepository
    ) {
        parent::__construct();
    }

    /**
     * Retrieves total orders and their progress.
     * 
     * @return array
     */
    public function getTotalOrdersProgress()
    {
        return [
            'previous' => $previous = $this->getTotalOrders($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalOrders($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Returns previous orders over time
     * 
     * @return array
     */
    public function getPreviousTotalOrdersOverTime(): array
    {
        return $this->getTotalOrdersOverTime($this->lastStartDate, $this->lastEndDate);
    }

    /**
     * Returns current orders over time
     * 
     * @return array
     */
    public function getCurrentTotalOrdersOverTime(): array
    {
        return $this->getTotalOrdersOverTime($this->startDate, $this->endDate);
    }

    /**
     * Retrieves total orders
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return int
     */
    public function getTotalOrders($startDate, $endDate): int
    {
        return $this->orderRepository
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Returns orders over time
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getTotalOrdersOverTime($startDate, $endDate): array
    {
        return $this->getOverTimeStats(
            $startDate,
            $endDate,
            'COUNT(*)'
        );
    }

    /**
     * Retrieves today orders and their progress.
     * 
     * @return array
     */
    public function getTodayOrdersProgress(): array
    {
        return [
            'previous' => $previous = $this->getTotalOrders(now()->subDay()->startOfDay(), now()->subDay()->endOfDay()),
            'current'  => $current = $this->getTotalOrders(now()->today(), now()->endOfDay()),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves orders
     * 
     * @return array
     */
    public function getTodayOrders()
    {
        return $this->orderRepository
            ->with(['addresses', 'payment', 'items'])
            ->whereBetween('orders.created_at', [now()->today(), now()->endOfDay()])
            ->get();
    }

    /**
     * Retrieves total sales and their progress.
     * 
     * @return array
     */
    public function getTotalSalesProgress(): array
    {
        return [
            'previous'        => $previous = $this->getTotalSales($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTotalSales($this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves today sales and their progress.
     * 
     * @return array
     */
    public function getTodaySalesProgress(): array
    {
        return [
            'previous' => $previous = $this->getTotalSales(now()->subDay()->startOfDay(), now()->subDay()->endOfDay()),
            'current'  => $current = $this->getTotalSales(now()->today(), now()->endOfDay()),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total sales
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return float
     */
    public function getTotalSales($startDate, $endDate): float
    {
        return $this->orderRepository
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('base_grand_total_invoiced - base_grand_total_refunded'));
    }

    /**
     * Returns previous sales over time
     * 
     * @return array
     */
    public function getPreviousTotalSalesOverTime(): array
    {
        return $this->getTotalSalesOverTime($this->lastStartDate, $this->lastEndDate);
    }

    /**
     * Returns current sales over time
     * 
     * @return array
     */
    public function getCurrentTotalSalesOverTime(): array
    {
        return $this->getTotalSalesOverTime($this->startDate, $this->endDate);
    }

    /**
     * Returns sales over time
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getTotalSalesOverTime($startDate, $endDate): array
    {
        return $this->getOverTimeStats(
            $startDate,
            $endDate,
            'SUM(base_grand_total_invoiced - base_grand_total_refunded)'
        );
    }

    /**
     * Returns orders over time
     * 
     * @param  string  $period
     * @return array
     */
    public function getAllSalesOverTime($period = 'day', $includeEmpty = true): array
    {
        return $this->getOverTimeStats(
            $this->startDate,
            $this->endDate,
            'SUM(base_grand_total_invoiced - base_grand_total_refunded)',
            $period
        );
    }

    /**
     * Retrieves average sales and their progress.
     * 
     * @return array
     */
    public function getAverageSalesProgress(): array
    {
        return [
            'previous'        => $previous = $this->getAverageSales($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getAverageSales($this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves average sales
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getAverageSales($startDate, $endDate): float
    {
        return $this->orderRepository
            ->whereBetween('created_at', [$startDate, $endDate])
            ->avg(DB::raw('base_grand_total_invoiced - base_grand_total_refunded'));
    }

    /**
     * Returns previous average sales over time
     * 
     * @return array
     */
    public function getPreviousAverageSalesOverTime(): array
    {
        return $this->getAverageSalesOverTime($this->lastStartDate, $this->lastEndDate);
    }

    /**
     * Returns current average sales over time
     * 
     * @return array
     */
    public function getCurrentAverageSalesOverTime(): array
    {
        return $this->getAverageSalesOverTime($this->startDate, $this->endDate);
    }

    /**
     * Returns average sales over time
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getAverageSalesOverTime($startDate, $endDate): array
    {
        return $this->getOverTimeStats(
            $startDate,
            $endDate,
            'AVG(base_grand_total_invoiced - base_grand_total_refunded)'
        );
    }

    /**
     * Retrieves refunds and their progress.
     * 
     * @return array
     */
    public function getRefundsProgress(): array
    {
        return [
            'previous'        => $previous = $this->getRefunds($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getRefunds($this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves refunds
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getRefunds($startDate, $endDate): float
    {
        return $this->orderRepository
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('base_grand_total_refunded'));
    }

    /**
     * Returns previous refunds over time
     * 
     * @return array
     */
    public function getPreviousRefundsOverTime(): array
    {
        return $this->getRefundsOverTime($this->lastStartDate, $this->lastEndDate);
    }

    /**
     * Returns current refunds over time
     * 
     * @return array
     */
    public function getCurrentRefundsOverTime(): array
    {
        return $this->getRefundsOverTime($this->startDate, $this->endDate);
    }

    /**
     * Returns refunds over time
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getRefundsOverTime($startDate, $endDate): array
    {
        return $this->getOverTimeStats(
            $startDate,
            $endDate,
            'SUM(base_grand_total_refunded)'
        );
    }

    /**
     * Retrieves tax collected and their progress.
     * 
     * @return array
     */
    public function getTaxCollectedProgress(): array
    {
        return [
            'previous'        => $previous = $this->getTaxCollected($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTaxCollected($this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves tax collected
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getTaxCollected($startDate, $endDate): float
    {
        return $this->orderRepository
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('base_tax_amount_invoiced - base_tax_amount_refunded'));
    }

    /**
     * Returns previous tax collected over time
     * 
     * @return array
     */
    public function getPreviousTaxCollectedOverTime(): array
    {
        return $this->getTaxCollectedOverTime($this->lastStartDate, $this->lastEndDate);
    }

    /**
     * Returns current tax collected over time
     * 
     * @return array
     */
    public function getCurrentTaxCollectedOverTime(): array
    {
        return $this->getTaxCollectedOverTime($this->startDate, $this->endDate);
    }

    /**
     * Returns tax collected over time
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getTaxCollectedOverTime($startDate, $endDate): array
    {
        return $this->getOverTimeStats(
            $startDate,
            $endDate,
            'SUM(base_tax_amount_invoiced - base_tax_amount_refunded)'
        );
    }

    /**
     * Returns top tax categories
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopTaxCategories(): Collection
    {
        return $this->orderItemRepository
            ->leftJoin('tax_categories', 'order_items.tax_category_id', '=', 'tax_categories.id')
            ->select('tax_categories.id as tax_category_id', 'tax_categories.name')
            ->addSelect(DB::raw('SUM(base_tax_amount_invoiced - base_tax_amount_refunded) as total'))
            ->whereBetween('order_items.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('tax_category_id')
            ->groupBy('tax_category_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    /**
     * Retrieves shipping collected and their progress.
     * 
     * @return array
     */
    public function getShippingCollectedProgress(): array
    {
        return [
            'previous'        => $previous = $this->getShippingCollected($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getShippingCollected($this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves shipping collected
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getShippingCollected($startDate, $endDate): float
    {
        return $this->orderRepository
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('base_shipping_invoiced - base_shipping_refunded'));
    }

    /**
     * Returns previous shipping collected over time
     * 
     * @return array
     */
    public function getPreviousShippingCollectedOverTime(): array
    {
        return $this->getShippingCollectedOverTime($this->lastStartDate, $this->lastEndDate);
    }

    /**
     * Returns current shipping collected over time
     * 
     * @return array
     */
    public function getCurrentShippingCollectedOverTime(): array
    {
        return $this->getShippingCollectedOverTime($this->startDate, $this->endDate);
    }

    /**
     * Returns shipping collected over time
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getShippingCollectedOverTime($startDate, $endDate): array
    {
        return $this->getOverTimeStats(
            $startDate,
            $endDate,
            'SUM(base_shipping_invoiced - base_shipping_refunded)'
        );
    }

    /**
     * Returns top shipping methods
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopShippingMethods(): Collection
    {
        return $this->orderRepository
            ->select('shipping_title as title')
            ->addSelect(DB::raw('SUM(base_shipping_invoiced - base_shipping_refunded) as total'))
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('shipping_method')
            ->groupBy('shipping_method')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    /**
     * Returns top payment methods
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopPaymentMethods(): Collection
    {
        return $this->orderRepository
            ->leftJoin('order_payment', 'orders.id', '=', 'order_payment.order_id')
            ->select('method', 'method_title as title')
            ->addSelect(DB::raw('COUNT(*) as total'))
            ->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
            ->groupBy('method')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
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
     * Retrieves total unique cart users
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getTotalUniqueOrdersUsers($startDate, $endDate): int
    {
        return $this->orderRepository
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('CONCAT(customer_email, "-", customer_id)'))
            ->get()
            ->count();
    }

    /**
     * Returns over time stats.
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $valueColumn
     * @param  string  $period
     * @return array
     */
    public function getOverTimeStats($startDate, $endDate, $valueColumn, $period = 'auto'): array
    {
        $config = $this->getTimeInterval($startDate, $endDate, $period);

        $groupColumn = $config['group_column'];

        $results = $this->orderRepository
            ->select(
                DB::raw("$groupColumn AS date"),
                DB::raw("$valueColumn AS total"),
                DB::raw("COUNT(*) AS count")
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw($groupColumn))
            ->get();

        foreach ($config['intervals'] as $interval) {
            $total = $results->where('date', $interval['filter'])->first();

            $stats[] = [
                'label' => $interval['start'],
                'total' => $total?->total ?? 0,
                'count' => $total?->count ?? 0,
            ];
        }

        return $stats;
    }
}