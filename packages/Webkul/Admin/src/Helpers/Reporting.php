<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Webkul\Admin\Helpers\Reporting\Sale;
use Webkul\Admin\Helpers\Reporting\Product;
use Webkul\Admin\Helpers\Reporting\Customer;
use Webkul\Admin\Helpers\Reporting\Visitor;

class Reporting
{
    /**
     * Create a controller instance.
     * 
     * @param  \Webkul\Admin\Helpers\Reporting\Sale  $saleReporting
     * @param  \Webkul\Admin\Helpers\Reporting\Product  $productReporting
     * @param  \Webkul\Admin\Helpers\Reporting\Customer  $customerReporting
     * @param  \Webkul\Admin\Helpers\Reporting\Visitor  $visitorReporting
     * @return void
     */
    public function __construct(
        protected Sale $saleReporting,
        protected Product $productReporting,
        protected Customer $customerReporting,
        protected Visitor $visitorReporting,
    )
    {
    }

    /**
     * Returns the overall statistics.
     * 
     * @return array
     */
    public function getOverAllStats(): array
    {
        return [
            'total_customers'       => $this->customerReporting->getTotalCustomersProgress(),
            'total_orders'          => $this->saleReporting->getTotalOrdersProgress(),
            'total_sales'           => $this->saleReporting->getTotalSalesProgress(),
            'avg_sales'             => $this->saleReporting->getAvgSalesProgress(),
            'total_unpaid_invoices' => $this->saleReporting->getTotalPendingInvoicesAmount(),
        ];
    }

    /**
     * Returns the today statistics.
     * 
     * @return array
     */
    public function getTodayStats(): array
    {
        $todayStartDate = now()->today();

        $todayEndDate = now()->endOfDay();

        return [
            'total_sales'     => $this->saleReporting->getTotalSalesProgress($todayStartDate, $todayEndDate),
            'total_orders'    => $this->saleReporting->getTotalOrdersProgress($todayStartDate, $todayEndDate),
            'total_customers' => $this->customerReporting->getTotalCustomersProgress($todayStartDate, $todayEndDate),
            'orders'          => $this->saleReporting->getOrders($todayStartDate, $todayEndDate),
        ];
    }

    /**
     * Returns the today statistics.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStockThresholdStats(): Collection
    {
        return $this->productReporting->getStockThreshold();
    }

    /**
     * Returns sales statistics.
     * 
     * @return array
     */
    public function getSalesStats(): array
    {
        return [
            'total_orders'    => $this->saleReporting->getTotalOrdersProgress(),
            'total_sales'     => $this->saleReporting->getTotalSalesProgress(),
            'sales_over_time' => $this->saleReporting->getSalesOverTime(),
        ];
    }

    /**
     * Returns visitors statistics.
     * 
     * @return array
     */
    public function getVisitorStats(): array
    {
        return [
            'total_visitors'     => $this->visitorReporting->getTotalVisitorsProgress(),
            'unique_visitors'    => $this->visitorReporting->getTotalUniqueVisitorsProcess(),
            'visitors_over_time' => $this->visitorReporting->getVisitorsOverTime(),
        ];
    }

    /**
     * Returns top products statistics.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopProductsStats(): Collection
    {
        return $this->saleReporting->getTopSellingProducts();
    }

    /**
     * Returns top customers statistics.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopCustomersStats(): Collection
    {
        return $this->saleReporting->getCustomersWithMostSales();
    }

    /**
     * Get the start date.
     * 
     * @return \Carbon\Carbon
     */
    public function getStartDate(): Carbon
    {
        return $this->saleReporting->getStartDate();
    }

    /**
     * Get the end date.
     * 
     * @return \Carbon\Carbon
     */
    public function getEndDate(): Carbon
    {
        return $this->saleReporting->getEndDate();
    }
}