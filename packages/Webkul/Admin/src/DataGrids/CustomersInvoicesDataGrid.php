<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CustomersInvoicesDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('invoices')
            ->leftJoin('orders as ors', 'invoices.order_id', '=', 'ors.id')
            ->select(
                'invoices.id as id',
                'ors.increment_id as order_id',
                'invoices.state as state',
                'ors.channel_name as channel_name',
                'invoices.base_grand_total as base_grand_total',
                'invoices.created_at as created_at'
            )
            ->where('ors.customer_id', request('id'));

        // $this->addFilter('id', 'invoices.id');
        // $this->addFilter('order_id', 'ors.increment_id');
        // $this->addFilter('base_grand_total', 'invoices.base_grand_total');
        // $this->addFilter('created_at', 'invoices.created_at');

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.sales.invoices.invoice-id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.datagrid.invoice-date'),
            'type'       => 'datetime',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'channel_name',
            'label'      => trans('admin::app.datagrid.channel-name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'order_id',
            'label'      => trans('admin::app.datagrid.order-id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                return '<a href="' . route('admin.sales.orders.view', $value->order_id) . '">' . $value->order_id . '</a>';
            },
        ]);

        $this->addColumn([
            'index'      => 'base_grand_total',
            'label'      => trans('admin::app.datagrid.grand-total'),
            'type'       => 'price',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'state',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                if ($value->state == 'paid') {
                    return '<span class="badge badge-md badge-success">' . trans('admin::app.sales.invoices.status-paid') . '</span>';
                } elseif (
                    $value->state == 'pending'
                    || $value->state == 'pending_payment'
                ) {
                    return '<span class="badge badge-md badge-warning">' . trans('admin::app.sales.invoices.status-pending') . '</span>';
                } elseif ($value->state == 'overdue') {
                    return '<span class="badge badge-md badge-info">' . trans('admin::app.sales.invoices.status-overdue') . '</span>';
                }

                return $value->state;
            },
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon-eye',
            'title'  => trans('admin::app.datagrid.view'),
            'method' => 'GET',
        ]);
    }
}
