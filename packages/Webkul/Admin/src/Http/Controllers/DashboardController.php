<?php

namespace Webkul\Admin\Http\Controllers;

use Webkul\Admin\Helpers\Reporting;

class DashboardController extends Controller
{
    /**
     * Create a controller instance.
     * 
     * @param  \Webkul\Admin\Helpers\Reporting  $reportingHelper
     * @return void
     */
    public function __construct(protected Reporting $reportingHelper)
    {
    }

    /**
     * Dashboard page.
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->reportingHelper
            ->setStartDate(request()->date('start'))
            ->setEndDate(request()->date('end'));

        if (request()->ajax()) {
            return response()->json([
                'statistics' => [
                    'total_orders'             => $this->reportingHelper->getTotalOrders(),
                    'total_sales'              => $this->reportingHelper->getTotalSales(),
                    'total_visitors'           => $this->reportingHelper->getTotalVisitors(),
                    'unique_visitors'          => $this->reportingHelper->getUniqueVisitors(),
                    'top_selling_categories'   => $this->reportingHelper->getTopSellingCategories(),
                    'top_selling_products'     => $this->reportingHelper->getTopSellingProducts(),
                    'customer_with_most_sales' => $this->reportingHelper->getCustomerWithMostSales(),
                    'visitor_graph'            => $this->reportingHelper->generateVisitorGraph(),
                    'sale_graph'               => $this->reportingHelper->generateSaleGraph(),
                ],
                'startDate'  => $this->reportingHelper->getStartDate()->format('d M'),
                'endDate'    => $this->reportingHelper->getEndDate()->format('d M'),
            ]);
        }

        return view('admin::dashboard.index')
            ->with([
                'statistics' => [
                    'total_customers'       => $this->reportingHelper->getTotalCustomers(),
                    'total_orders'          => $this->reportingHelper->getTotalOrders(),
                    'total_sales'           => $this->reportingHelper->getTotalSales(),
                    'avg_sales'             => $this->reportingHelper->getAvgSales(),
                    'total_unpaid_invoices' => $this->reportingHelper->getTotalAmountOfPendingInvoices(),
                    'stock_threshold'       => $this->reportingHelper->getStockThreshold(),
                    'today_details'         => $this->reportingHelper->getTodayDetails(),
                ],
                'startDate'  => $this->reportingHelper->getStartDate(),
                'endDate'    => $this->reportingHelper->getEndDate(),
            ]);
    }
}
