<?php

namespace Webkul\Admin\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * DataGridExport class
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class DataGridExport implements FromView, ShouldAutoSize
{

    /**
     * DataGrid instance
     *
     * @var mixed
     */
    public $gridData;

    /**
     * Create a new instance.
     *
     * @param mixed DataGrid
     * @return void
     */
    public function __construct($gridData)
    {
        $this->gridData = $gridData;
    }

     /**
     * function to create a blade view for export.
     *
     */
    public function view(): View
    {
        $pagination = false;

        dd($this->gridData);

        // return view('admin::export.export', [
        //     'results' => $this->gridData->render($pagination)->results,
        //     'columns' => $this->gridData->render($pagination)->columns,
        // ]);
    }
}