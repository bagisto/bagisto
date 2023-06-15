<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Webkul\Admin\Services\DashboardService;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected array $_config;

    /**
     * Create a controller instance.
     *
     * @param DashboardService $dashboardService
     */
    public function __construct(
        protected DashboardService $dashboardService
    )
    {
        $this->_config = request('_config');
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

        return view($this->_config['view'], compact('statistics'))
            ->with([
                'startDate' => $this->dashboardService->getStartDate(),
                'endDate' => $this->dashboardService->getEndDate()
            ]);
    }
}
