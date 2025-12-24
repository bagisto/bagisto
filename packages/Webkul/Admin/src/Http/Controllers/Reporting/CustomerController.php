<?php

namespace Webkul\Admin\Http\Controllers\Reporting;

class CustomerController extends Controller
{
    /**
     * Request param functions.
     *
     * @var array
     */
    protected $typeFunctions = [
        'total-customers'             => 'getTotalCustomersStats',
        'customers-traffic'           => 'getCustomersTrafficStats',
        'customers-with-most-sales'   => 'getCustomersWithMostSales',
        'customers-with-most-orders'  => 'getCustomersWithMostOrders',
        'customers-with-most-reviews' => 'getCustomersWithMostReviews',
        'top-customer-groups'         => 'getTopCustomerGroups',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin::reporting.customers.index')->with([
            'startDate' => $this->reportingHelper->getStartDate(),
            'endDate'   => $this->reportingHelper->getEndDate(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function view()
    {
        if (! array_key_exists(request()->query('type'), $this->typeFunctions)) {
            abort(404);
        }

        return view('admin::reporting.view')->with([
            'entity'    => 'customers',
            'startDate' => $this->reportingHelper->getStartDate(),
            'endDate'   => $this->reportingHelper->getEndDate(),
        ]);
    }
}
