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

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('invoices')
                ->leftJoin('orders as ors', 'invoices.order_id', '=', 'ors.id')
                ->select('invoices.id as id', 'ors.increment_id as order_id', 'invoices.state as state', 'invoices.base_grand_total as base_grand_total', 'invoices.created_at as created_at');

        $this->addFilter('id', 'invoices.id');
        $this->addFilter('order_id', 'ors.increment_id');
        $this->addFilter('base_grand_total', 'invoices.base_grand_total');
        $this->addFilter('created_at', 'invoices.created_at');

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
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'order_id',
            'label' => trans('admin::app.datagrid.order-id'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('admin::app.datagrid.grand-total'),
            'type' => 'price',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('admin::app.datagrid.invoice-date'),
            'type' => 'datetime',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'title' => 'Order Invoice View',
            'method' => 'GET', // use GET request only for redirect purposes
            'route' => 'admin.sales.invoices.view',
            'icon' => 'icon eye-icon'
        ]);
    }
}