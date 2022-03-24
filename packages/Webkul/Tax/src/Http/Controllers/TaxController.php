<?php

namespace Webkul\Tax\Http\Controllers;

use Webkul\Admin\DataGrids\TaxCategoryDataGrid;

class TaxController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');

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
            return app(TaxCategoryDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }
}
