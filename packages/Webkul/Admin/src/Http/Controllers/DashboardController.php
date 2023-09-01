<?php

namespace Webkul\Admin\Http\Controllers;

use Webkul\Admin\Services\DashboardService;

class DashboardController extends Controller
{
    /**
     * Create a controller instance.
     */
    public function __construct(protected DashboardService $dashboardService)
    {
    }

    /**
     * Dashboard page.
     */
    public function index()
    {
        if (request()->ajax()) {
            $statistics = $this->dashboardService
                ->setStartDate(request()->date('start'))
                ->setEndDate(request()->date('end'))
                ->getStatistics();

            return response()->json([
                'statistics' => $statistics,
                'startDate'  => $this->dashboardService->getStartDate()->format('d M'),
                'endDate'    => $this->dashboardService->getEndDate()->format('d M'),
            ]);
        }

        $statistics = $this->dashboardService
            ->setStartDate(request()->date('start'))
            ->setEndDate(request()->date('end'))
            ->getStatistics();

        return view('admin::dashboard.index', compact('statistics'))
            ->with([
                'startDate' => $this->dashboardService->getStartDate(),
                'endDate'   => $this->dashboardService->getEndDate(),
            ]);
    }
}
