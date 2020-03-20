<?php

namespace Webkul\Admin\Http\Controllers;

use Webkul\Admin\Exports\DataGridExport;
use Excel;

class ExportController extends Controller
{
    protected $exportableGrids = [
        'OrderDataGrid',
        'OrderInvoicesDataGrid',
        'OrderShipmentsDataGrid',
        'OrderRefundDataGrid',
        'CustomerDataGrid',
        'TaxRateDataGrid',
        'ProductDataGrid',
        'CMSPageDataGrid',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
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
        $criteria = request()->all();

        $format = $criteria['format'];

        $gridName = explode('\\', $criteria['gridName']);

        $path = '\Webkul\Admin\DataGrids'.'\\'.last($gridName);

        $proceed = false;

        foreach ($this->exportableGrids as $exportableGrid) {
            if (last($gridName) == $exportableGrid) {
                $proceed = true;
            }
        }

        if (! $proceed) {
            return redirect()->back();
        }

        $gridInstance = new $path;
        
        $records = $gridInstance->export();

        if (! count($records)) {
            session()->flash('warning', trans('admin::app.export.no-records'));

            return redirect()->back();
        }

        if ($format == 'csv') {
            return Excel::download(new DataGridExport($records), last($gridName).'.csv');
        }

        if ($format == 'xls') {
            return Excel::download(new DataGridExport($records), last($gridName).'.xlsx');
        }

        session()->flash('warning', trans('admin::app.export.illegal-format'));

        return redirect()->back();
    }
}