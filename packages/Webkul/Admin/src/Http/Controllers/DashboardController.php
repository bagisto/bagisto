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
        if (request()->ajax()) {
            return response()->json([
                'statistics' => [
                    'sales'     => $this->reportingHelper->getSalesStats(),
                    'visitors'  => $this->reportingHelper->getVisitorStats(),
                    'products'  => $this->reportingHelper->getTopProductsStats(),
                    'customers' => $this->reportingHelper->getTopCustomersStats(),
                ],
                'startDate' => $this->reportingHelper->getStartDate()->format('d M'),
                'endDate'   => $this->reportingHelper->getEndDate()->format('d M'),
            ]);
        }

        return view('admin::dashboard.index')
            ->with([
                'statistics' => [
                    'over_all'        => $this->reportingHelper->getOverAllStats(),
                    'today'           => $this->reportingHelper->getTodayStats(),
                    'stock_threshold' => $this->reportingHelper->getStockThresholdStats(),
                ],
                'startDate' => $this->reportingHelper->getStartDate(),
                'endDate'   => $this->reportingHelper->getEndDate(),
            ]);
    }
}
