<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Webkul\Admin\Services\DashboardService;

class DashboardController extends Controller
{
    /**
     * Create a controller instance.
     */
    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    /**
     * Dashboard page.
     */
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
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
