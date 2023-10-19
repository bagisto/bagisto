<?php

namespace Webkul\Admin\Http\Controllers;

use Webkul\Admin\Helpers\Dashboard;

class DashboardController extends Controller
{
    /**
     * Create a controller instance.
     * 
     * @param  \Webkul\Admin\Helpers\Dashboard  $dashboardHelper
     * @return void
     */
    public function __construct(protected Dashboard $dashboardHelper)
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
                    'sales'     => $this->dashboardHelper->getSalesStats(),
                    'visitors'  => $this->dashboardHelper->getVisitorStats(),
                    'products'  => $this->dashboardHelper->getTopProducts(),
                    'customers' => $this->dashboardHelper->getTopCustomers(),
                ],
                'startDate' => $this->dashboardHelper->getStartDate()->format('d M'),
                'endDate'   => $this->dashboardHelper->getEndDate()->format('d M'),
            ]);
        }

        return view('admin::dashboard.index')
            ->with([
                'statistics' => [
                    'over_all'        => $this->dashboardHelper->getOverAllStats(),
                    'today'           => $this->dashboardHelper->getTodayStats(),
                    'stock_threshold' => $this->dashboardHelper->getStockThresholdProducts(),
                ],
                'startDate' => $this->dashboardHelper->getStartDate(),
                'endDate'   => $this->dashboardHelper->getEndDate(),
            ]);
    }
}
