<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Sales\Models\Invoice;
use Webkul\Ui\DataGrid\DataGrid;

class InvoicesTransactionsDatagrid extends DataGrid
{

    protected string $index = 'id';

    protected string $sortOrder = 'desc';

    public function prepareQueryBuilder(): void
    {
        $queryBuilder = Invoice::query()
            ->leftJoin('invoices as inv', 'order_transactions.invoice_id', '=', 'inv.id')
            ->select('order_transactions.id as id', 'order_transactions.transaction_id as transaction_id', 'order_transactions.invoice_id as invoice_id', 'order_transactions.created_at as created_at')
            ->where('order_transactions.invoice_id', request('id'));


        $this->addFilter('id', 'order_transactions.id');
        $this->addFilter('transaction_id', 'order_transactions.transaction_id');
        $this->addFilter('order_id', 'ors.increment_id');
        $this->addFilter('created_at', 'order_transactions.created_at');

        $this->setQueryBuilder($queryBuilder);
    }

	/**
	 * @throws \Webkul\Ui\Exceptions\ColumnKeyException add column failed
	 */
	public function addColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'transaction_id',
            'label'      => trans('admin::app.datagrid.transaction-id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.datagrid.transaction-date'),
            'type'       => 'datetime',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

	/**
	 * @throws \Webkul\Ui\Exceptions\ActionKeyException add action failed
	 */
	public function prepareActions(): void
	{
        $this->addAction([
            'title'  => trans('admin::app.datagrid.view'),
            'method' => 'GET',
            'route'  => 'admin.sales.transactions.view',
            'icon'   => 'icon eye-icon',
        ]);
    }
}
