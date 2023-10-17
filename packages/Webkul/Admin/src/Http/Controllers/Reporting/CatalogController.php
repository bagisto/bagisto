<?php

namespace Webkul\Admin\Http\Controllers\Reporting;

use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Helpers\Reporting;

class CatalogController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin::reporting.catalog.index');
    }
}