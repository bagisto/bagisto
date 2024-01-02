<?php

namespace Webkul\Admin\Http\Controllers\Reporting;

use Maatwebsite\Excel\Facades\Excel;
use Webkul\Admin\Exports\ReportingExport;
use Webkul\Admin\Helpers\Reporting as ReportingHelper;
use Webkul\Admin\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Request param functions
     *
     * @var array
     */
    protected $typeFunctions = [
        'total-sales'         => 'getTotalSalesStats',
        'average-sales'       => 'getAverageSalesStats',
        'total-orders'        => 'getTotalOrdersStats',
        'purchase-funnel'     => 'getPurchaseFunnelStats',
        'abandoned-carts'     => 'getAbandonedCartsStats',
        'refunds'             => 'getRefundsStats',
        'tax-collected'       => 'getTaxCollectedStats',
        'shipping-collected'  => 'getShippingCollectedStats',
        'top-payment-methods' => 'getTopPaymentMethods',
    ];

    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(protected ReportingHelper $reportingHelper)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats()
    {
        $stats = $this->reportingHelper->{$this->typeFunctions[request()->query('type')]}();

        return response()->json([
            'statistics' => $stats,
            'date_range' => $this->reportingHelper->getDateRange(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewStats()
    {
        $stats = $this->reportingHelper->{$this->typeFunctions[request()->query('type')]}('table');

        return response()->json([
            'statistics' => $stats,
            'date_range' => $this->reportingHelper->getDateRange(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $stats = $this->reportingHelper->{$this->typeFunctions[request()->query('type')]}('table');

        return Excel::download(new ReportingExport($stats), request()->query('type') . '.' . request()->query('format'));
    }
}
