<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Sales\Models\Invoice;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Ui\DataGrid\Traits\ProvideDataGridPlus;

class CustomersInvoicesDataGrid extends DataGrid
{
    use ProvideDataGridPlus;

    /**
     * Index column.
     *
     * @var int
     */
    protected string $index = 'id';

    /**
     * Default sort order of datagrid.
     *
     * @var string
     */
    protected string $sortOrder = 'desc';

    /**
     * Prepare query.
     *
     * @return void
     */
    public function prepareQueryBuilder(): void
    {
        $queryBuilder = Invoice::query()
            ->leftJoin('orders as ors', 'invoices.order_id', '=', 'ors.id')
            ->select('invoices.id as id', 'ors.increment_id as order_id', 'invoices.state as state', 'ors.channel_name as channel_name', 'invoices.base_grand_total as base_grand_total', 'invoices.created_at as created_at')
            ->where('ors.customer_id', request('id'));

        $this->addFilter('id', 'invoices.id');
        $this->addFilter('order_id', 'ors.increment_id');
        $this->addFilter('base_grand_total', 'invoices.base_grand_total');
        $this->addFilter('created_at', 'invoices.created_at');

        $this->setQueryBuilder($queryBuilder);
    }

	/**
	 * Add columns.
	 *
	 * @throws \Webkul\Ui\Exceptions\ColumnKeyException add column failed
	 * @return void
	 */
	public function addColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.sales.invoices.invoice-id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.datagrid.invoice-date'),
            'type'       => 'date',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'channel_name',
            'label'      => trans('admin::app.datagrid.channel-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'order_id',
            'label'      => trans('admin::app.datagrid.order-id'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($value) {
                return '<a href="' . route('admin.sales.orders.view', $value->order_id) . '">' . $value->order_id . '</a>';
            }
        ]);

        $this->addColumn([
            'index'      => 'base_grand_total',
            'label'      => trans('admin::app.datagrid.grand-total'),
            'type'       => 'price',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

		$this->addColumn([
			'index'      => 'state',
			'label'      => trans('admin::app.datagrid.status'),
			'type'       => 'string',
			'sortable'   => true,
			'searchable' => true,
			'filterable' => true,
			'closure'    => function ($value) {
				switch ( $value->state ) {
					case 'paid':
						return '<span class="badge badge-md badge-success">' . trans('admin::app.sales.invoices.status-paid') . '</span>';
					case 'pending':
					case 'pending_payment':
						return '<span class="badge badge-md badge-warning">' . trans('admin::app.sales.invoices.status-pending') . '</span>';
					case 'overdue':
						return '<span class="badge badge-md badge-info">' . trans('admin::app.sales.invoices.status-overdue') . '</span>';
					default:
						return $value->state;
				}
			},
		]);
	}

	/**
	 * Prepare actions.
	 *
	 * @throws \Webkul\Ui\Exceptions\ActionKeyException add action failed
	 * @return void
	 */
	public function prepareActions(): void
	{
        $this->addAction([
            'title'  => trans('admin::app.datagrid.view'),
            'method' => 'GET',
            'route'  => 'admin.sales.invoices.view',
            'icon'   => 'icon eye-icon',
        ]);
    }
}
