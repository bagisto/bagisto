<?php

namespace Webkul\Admin\DataGrids\Sales;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class OrderTransactionsDataGrid extends DataGrid
{
    /**
     * Transaction status Paid.
     */
    const STATUS_PAID = 'paid';

    /**
     * Transaction status Pending.
     */
    const STATUS_PENDING = 'pending';

    /**
     * Transaction status Completed
     */
    const STATUS_COMPLETED = 'COMPLETED';

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('order_transactions')
            ->leftJoin('orders as ors', 'order_transactions.order_id', '=', 'ors.id')
            ->select(
                'order_transactions.id as id',
                'order_transactions.transaction_id as transaction_id',
                'order_transactions.invoice_id as invoice_id',
                'ors.increment_id as order_id',
                'order_transactions.created_at as created_at',
                'order_transactions.amount as amount',
                'order_transactions.status as status'
            );

        $this->addFilter('id', 'order_transactions.id');
        $this->addFilter('transaction_id', 'order_transactions.transaction_id');
        $this->addFilter('invoice_id', 'order_transactions.invoice_id');
        $this->addFilter('order_id', 'ors.increment_id');
        $this->addFilter('created_at', 'order_transactions.created_at');
        $this->addFilter('status', 'order_transactions.status');

        return $queryBuilder;
    }

    /**
     * Add Columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.sales.transactions.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'transaction_id',
            'label'      => trans('admin::app.sales.transactions.index.datagrid.transaction-id'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'amount',
            'label'      => trans('admin::app.sales.transactions.index.datagrid.transaction-amount'),
            'type'       => 'price',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'invoice_id',
            'label'      => trans('admin::app.sales.transactions.index.datagrid.invoice-id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'order_id',
            'label'      => trans('admin::app.sales.transactions.index.datagrid.order-id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.sales.transactions.index.datagrid.status'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => [
                        [
                            'label' => trans('admin::app.sales.transactions.index.datagrid.paid'),
                            'value' => self::STATUS_PAID,
                        ],
                        [
                            'label' => trans('admin::app.sales.transactions.index.datagrid.pending'),
                            'value' => self::STATUS_PENDING,
                        ],
                        [
                            'label' => trans('admin::app.sales.transactions.index.datagrid.completed'),
                            'value' => self::STATUS_COMPLETED,
                        ],
                    ],
                ],
            ],
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case self::STATUS_PAID:
                        return '<p class="label-active">'.trans('admin::app.sales.transactions.index.datagrid.paid').'</p>';

                    case self::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('admin::app.sales.transactions.index.datagrid.pending').'</p>';

                    case self::STATUS_COMPLETED:
                        return '<p class="label-completed">'.trans('admin::app.sales.transactions.index.datagrid.completed').'</p>';
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.sales.transactions.index.datagrid.transaction-date'),
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
        if (bouncer()->hasPermission('sales.shipments.view')) {
            $this->addAction([
                'icon'   => 'icon-view',
                'title'  => trans('admin::app.sales.transactions.index.datagrid.view'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.transactions.view', $row->id);
                },
            ]);
        }
    }
}
