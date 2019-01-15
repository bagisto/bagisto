<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * OrderInvoicesDataGrid Class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderInvoicesDataGrid extends DataGrid
{
    protected $index = 'id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('invoices')->select('id', 'order_id', 'state', 'grand_total', 'created_at');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'order_id',
            'label' => trans('admin::app.datagrid.order-id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'grand_total',
            'label' => trans('admin::app.datagrid.grand-total'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('admin::app.datagrid.invoice-date'),
            'type' => 'datetime',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'View',
            'route' => 'admin.sales.invoices.view',
            'icon' => 'icon eye-icon'
        ]);
    }

    public function prepareMassActions() {
        // $this->addMassAction([
        //     'type' => 'delete',
        //     'action' => route('admin.catalog.attributes.massdelete'),
        //     'method' => 'DELETE'
        // ]);
    }
}