<?php

namespace Webkul\BookingProduct\Http\Controllers\Admin;

use Webkul\BookingProduct\Http\Controllers\Controller;
use Webkul\BookingProduct\DataGrids\Admin\BookingDataGrid;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(BookingDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }
}