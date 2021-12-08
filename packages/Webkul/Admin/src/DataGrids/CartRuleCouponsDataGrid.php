<?php

namespace Webkul\Admin\DataGrids;

use Webkul\CartRule\Models\CartRule;
use Webkul\Ui\DataGrid\DataGrid;

class CartRuleCouponsDataGrid extends DataGrid
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
        $queryBuilder = CartRule::query()
            ->select('id')
            ->addSelect('id', 'code', 'limit', 'usage_per_customer', 'usage_throttle');

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
            'index'      => 'code',
            'label'      => trans('admin::app.datagrid.code'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'limit',
            'label'      => trans('admin::app.datagrid.limit'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'limit',
            'label'      => trans('admin::app.datagrid.limit'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

		$this->addColumn([
			'index'      => 'usage_per_customer',
			'label'      => trans('admin::app.datagrid.usage-per-customer'),
			'type'       => 'boolean',
			'searchable' => false,
			'sortable'   => true,
			'filterable' => true,
			'closure'    => function ($value) {
				return (int) $value->end_other_rules === 1
					? trans('admin::app.datagrid.true')
					: trans('admin::app.datagrid.false');
			},
		]);
    }
}
