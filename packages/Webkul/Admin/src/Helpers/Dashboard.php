<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Webkul\Admin\Helpers\Reporting\Sale;
use Webkul\Admin\Helpers\Reporting\Product;
use Webkul\Admin\Helpers\Reporting\Customer;
use Webkul\Admin\Helpers\Reporting\Visitor;

class Dashboard
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
        protected Visitor $visitorReporting
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
            'avg_sales'             => $this->saleReporting->getAverageSalesProgress(),
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
        return [
            'total_sales'     => $this->saleReporting->getTodaySalesProgress(),
            'total_orders'    => $this->saleReporting->getTodayOrdersProgress(),
            'total_customers' => $this->customerReporting->getTodayCustomersProgress(),
            'orders'          => $this->saleReporting->getTodayOrders(),
        ];
    }

    /**
     * Returns the today statistics.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStockThresholdProducts(): Collection
    {
        return $this->productReporting->getStockThresholdProducts(5);
    }

    /**
     * Returns sales statistics.
     * 
     * @return array
     */
    public function getSalesStats(): array
    {
        return [
            'total_orders' => $this->saleReporting->getTotalOrdersProgress(),
            'total_sales'  => $this->saleReporting->getTotalSalesProgress(),
            'over_time'    => $this->saleReporting->getCurrentTotalSalesOverTime(),
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
            'total'     => $this->visitorReporting->getTotalVisitorsProgress(),
            'unique'    => $this->visitorReporting->getTotalUniqueVisitorsProgress(),
            'over_time' => $this->visitorReporting->getCurrentTotalVisitorsOverTime(),
        ];
    }

    /**
     * Returns top products statistics.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopProducts(): Collection
    {
        return $this->productReporting->getTopSellingProductsByRevenue();
    }

    /**
     * Returns top customers statistics.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopCustomers(): Collection
    {
        $customers = $this->customerReporting->getCustomersWithMostSales();

        $customers->map(function($customer) {
            $customer->formatted_total = core()->formatBasePrice($customer->total);
        });

        return $customers;
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