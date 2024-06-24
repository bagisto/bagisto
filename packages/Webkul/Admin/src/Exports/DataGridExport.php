<?php

namespace Webkul\Admin\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Webkul\DataGrid\DataGrid;

class DataGridExport implements FromView, ShouldAutoSize
{
    /**
     * Create a new instance.
     *
     * @param mixed DataGrid
     * @return void
     */
    public function __construct(protected DataGrid $datagrid) {}

    /**
     * function to create a blade view for export.
     */
    public function view(): View
    {
        return view('admin::components.datagrid.export.temp', [
            'columns' => $this->datagrid->getColumns(),
            'records' => $this->datagrid->getQueryBuilder()->get(),
        ]);
    }
}
