<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Core\Models\SubscribersList;
use Webkul\Ui\DataGrid\DataGrid;

class NewsLetterDataGrid extends DataGrid
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
		$queryBuilder = SubscribersList::query()->select('subscribers_list.id', 'subscribers_list.is_subscribed as status', 'subscribers_list.email');

		$this->addFilter('status', 'subscribers_list.is_subscribed');

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
			'label'      => trans('admin::app.datagrid.id'),
			'type'       => 'number',
			'searchable' => false,
			'sortable'   => true,
			'filterable' => true,
		]);

		$this->addColumn([
			'index'      => 'status',
			'label'      => trans('admin::app.datagrid.subscribed'),
			'type'       => 'boolean',
			'searchable' => true,
			'sortable'   => true,
			'filterable' => true,
			'closure'    => function ($value) {
				if ($value->status === 1) {
					return trans('admin::app.datagrid.true');
				}
				else {
					return trans('admin::app.datagrid.false');
				}
			},
		]);

		$this->addColumn([
			'index'      => 'email',
			'label'      => trans('admin::app.datagrid.email'),
			'type'       => 'string',
			'searchable' => true,
			'sortable'   => true,
			'filterable' => true,
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
			'title'  => trans('admin::app.datagrid.edit'),
			'method' => 'GET',
			'route'  => 'admin.customers.subscribers.edit',
			'icon'   => 'icon pencil-lg-icon',
		]);

		$this->addAction([
			'title'        => trans('admin::app.datagrid.delete'),
			'method'       => 'POST',
			'route'        => 'admin.customers.subscribers.delete',
			'confirm_text' => trans('ui::app.datagrid.massaction.delete', [ 'resource' => 'Exchange Rate' ]),
			'icon'         => 'icon trash-icon',
		]);
	}
}
