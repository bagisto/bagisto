<?php

namespace Webkul\Admin\DataGrids\Sales;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class OrderInvoicesDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $dbPrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('invoices')
            ->leftJoin('orders as ors', 'invoices.order_id', '=', 'ors.id')
            ->select(
                'invoices.id as id',
                'ors.increment_id as order_id',
                'invoices.state as state',
                'invoices.base_grand_total as base_grand_total',
                'invoices.created_at as created_at'
            )
            ->selectRaw("CASE WHEN {$dbPrefix}invoices.increment_id IS NOT NULL THEN {$dbPrefix}invoices.increment_id ELSE {$dbPrefix}invoices.id END AS increment_id");

        $this->addFilter('increment_id', 'invoices.increment_id');
        $this->addFilter('order_id', 'ors.increment_id');
        $this->addFilter('base_grand_total', 'invoices.base_grand_total');
        $this->addFilter('created_at', 'invoices.created_at');

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
            'index'      => 'increment_id',
            'label'      => trans('admin::app.sales.invoices.index.datagrid.id'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'order_id',
            'label'      => trans('admin::app.sales.invoices.index.datagrid.order-id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'base_grand_total',
            'label'      => trans('admin::app.sales.invoices.index.datagrid.grand-total'),
            'type'       => 'price',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'state',
            'label'      => trans('admin::app.sales.invoices.index.datagrid.status'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                if ($value->state == 'paid') {
                    return '<p class="label-active">'.trans('admin::app.sales.invoices.index.datagrid.paid').'</p>';
                } elseif (
                    $value->state == 'pending'
                    || $value->state == 'pending_payment'
                ) {
                    return '<p class="label-pending">'.trans('admin::app.sales.invoices.index.datagrid.pending').'</p>';
                } elseif ($value->state == 'overdue') {
                    return '<p class="label-cancel">'.trans('admin::app.sales.invoices.index.datagrid.overdue').'</p>';
                }

                return $value->state;
            },
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.sales.invoices.index.datagrid.invoice-date'),
            'type'       => 'date_range',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('sales.invoices.view')) {
            $this->addAction([
                'icon'   => 'icon-view',
                'title'  => trans('admin::app.sales.invoices.index.datagrid.view'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.invoices.view', $row->id);
                },
            ]);
        }
    }
}
