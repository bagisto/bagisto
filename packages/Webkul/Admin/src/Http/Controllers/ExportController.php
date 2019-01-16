<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Exports\DataGridExport;
use Excel;

/**
 * Export controlller
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ExportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * function for export datagrid
     *
     * @return \Illuminate\Http\Response
    */
    public function export()
    {
        $results = request()->all()['gridData'];

        $data = json_decode($results, true);

        $results = (object) $data;

        $file_name = class_basename($results);

        if (request()->all()['format'] == 'csv') {
            return Excel::download(new DataGridExport($results), $file_name.'.csv');
        } else {
            return Excel::download(new DataGridExport($results), $file_name.'.xlsx');
        }
    }
}