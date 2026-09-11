<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Helpers\Dashboard;

class DashboardController extends Controller
{
    /**
     * Request param functions
     *
     * @var array
     */
    protected $typeFunctions = [
        'over-all' => 'getOverAllStats',
        'today' => 'getTodayStats',
        'stock-threshold-products' => 'getStockThresholdProducts',
        'total-sales' => 'getSalesStats',
        'top-selling-products' => 'getTopSellingProducts',
        'top-customers' => 'getTopCustomers',
    ];

    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(protected Dashboard $dashboardHelper) {}

    /**
     * Dashboard page.
     *
     * @return View|JsonResponse
     */
    public function index()
    {
        return view('admin::dashboard.index')->with([
            'startDate' => $this->dashboardHelper->getStartDate(),
            'endDate' => $this->dashboardHelper->getEndDate(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function stats()
    {
        $stats = $this->dashboardHelper->{$this->resolveTypeFunction()}();

        return response()->json([
            'statistics' => $stats,
            'date_range' => $this->dashboardHelper->getDateRange(),
        ]);
    }

    /**
     * Resolve the requested type into a valid function name.
     *
     * @return string
     */
    protected function resolveTypeFunction()
    {
        $type = request()->query('type');

        if (
            ! is_string($type)
            || ! array_key_exists($type, $this->typeFunctions)
        ) {
            abort(404);
        }

        return $this->typeFunctions[$type];
    }
}
