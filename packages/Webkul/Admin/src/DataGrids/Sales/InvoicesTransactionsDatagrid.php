<?php

namespace Webkul\Admin\DataGrids\Sales;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class InvoicesTransactionsDatagrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('order_transactions')
            ->leftJoin('invoices as inv', 'order_transactions.invoice_id', '=', 'inv.id')
            ->select(
                'order_transactions.id as id',
                'order_transactions.transaction_id as transaction_id',
                'order_transactions.invoice_id as invoice_id',
                'order_transactions.created_at as created_at'
            )
            ->where('order_transactions.invoice_id', request('id'));

        $this->addFilter('id', 'order_transactions.id');
        $this->addFilter('transaction_id', 'order_transactions.transaction_id');
        $this->addFilter('order_id', 'ors.increment_id');
        $this->addFilter('created_at', 'order_transactions.created_at');

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
            'label'      => trans('admin::app.sales.invoice-transaction.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'transaction_id',
            'label'      => trans('admin::app.sales.invoice-transaction.index.datagrid.transaction-id'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.sales.invoice-transaction.index.datagrid.transaction-date'),
            'type'       => 'datetime',
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
        $this->addAction([
            'icon'   => 'icon-view',
            'title'  => trans('admin::app.sales.invoice-transaction.index.datagrid.view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.sales.transactions.view', $row->id);
            },
        ]);
    }
}
