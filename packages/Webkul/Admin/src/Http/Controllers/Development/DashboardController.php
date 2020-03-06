<?php

namespace Webkul\Admin\Http\Controllers\Development;

use Webkul\Admin\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin::settings.development.dashboard');
    }
}