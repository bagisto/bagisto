<?php

namespace Webkul\Shop\DataGrids;

use Webkul\Sales\Models\Order;
use Webkul\Ui\DataGrid\DataGrid;

class OrderDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected string $index = 'id';

    /**
     * Sort order.
     *
     * @var string
     */
    protected string $sortOrder = 'desc';

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder(): void
    {
        $queryBuilder =Order::query()
            ->addSelect('order.id', 'order.increment_id', 'order.status', 'order.created_at', 'order.grand_total', 'order.order_currency_code')
            ->where('customer_id', auth()->guard('customer')->user()->id);

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
            'index'      => 'increment_id',
            'label'      => trans('shop::app.customer.account.order.index.order_id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('shop::app.customer.account.order.index.date'),
            'type'       => 'datetime',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'grand_total',
            'label'      => trans('shop::app.customer.account.order.index.total'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($value) {
                return core()->formatPrice($value->grand_total, $value->order_currency_code);
            },
        ]);

		$this->addColumn([
			'index'      => 'status',
			'label'      => trans('shop::app.customer.account.order.index.status'),
			'type'       => 'string',
			'searchable' => false,
			'sortable'   => true,
			'filterable' => true,
			'closure'    => function ($value) {
				switch ( $value->status ) {
					case 'processing':
						return '<span class="badge badge-md badge-success">' . trans('shop::app.customer.account.order.index.processing') . '</span>';
					case 'completed':
						return '<span class="badge badge-md badge-success">' . trans('shop::app.customer.account.order.index.completed') . '</span>';
					case 'canceled':
						return '<span class="badge badge-md badge-danger">' . trans('shop::app.customer.account.order.index.canceled') . '</span>';
					case 'closed':
						return '<span class="badge badge-md badge-info">' . trans('shop::app.customer.account.order.index.closed') . '</span>';
					case 'pending':
						return '<span class="badge badge-md badge-warning">' . trans('shop::app.customer.account.order.index.pending') . '</span>';
					case 'pending_payment':
						return '<span class="badge badge-md badge-warning">' . trans('shop::app.customer.account.order.index.pending-payment') . '</span>';
					case 'fraud':
						return '<span class="badge badge-md badge-danger">' . trans('shop::app.customer.account.order.index.fraud') . '</span>';
					default:
						return '';
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
            'title'  => trans('ui::app.datagrid.view'),
            'type'   => 'View',
            'method' => 'GET',
            'route'  => 'customer.orders.view',
            'icon'   => 'icon eye-icon',
        ], true);
    }
}
