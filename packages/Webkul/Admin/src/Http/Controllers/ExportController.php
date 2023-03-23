<?php

namespace Webkul\Admin\Http\Controllers;

use Excel;
use Webkul\Admin\Exports\DataGridExport;

class ExportController extends Controller
{
    /**
     * Export datagrid.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $criteria = request()->all();

        $format = $criteria['format'];

        $gridName = explode('\\', $criteria['gridName']);

        $gridInstance = app($criteria['gridName']);

        $records = $gridInstance->export();

        if (! count($records)) {
            session()->flash('warning', trans('admin::app.export.no-records'));

            return redirect()->back();
        }

        if ($format == 'csv') {
            return Excel::download(new DataGridExport($records), last($gridName) . '.csv');
        } elseif ($format == 'xls') {
            return Excel::download(new DataGridExport($records), last($gridName) . '.xlsx');
        }

        session()->flash('warning', trans('admin::app.export.illegal-format'));

        return redirect()->back();
    }
}
