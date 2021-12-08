<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Attribute\Models\Attribute;
use Webkul\Ui\DataGrid\DataGrid;

class AttributeDataGrid extends DataGrid
{
    /**
     * Set index columns, ex: id.
     *
     * @var string
     */
    protected string $index = 'id';

    /**
     * Default sort order of datagrid.
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
		$queryBuilder = Attribute::query()
						  ->select('id')
						  ->addSelect('id', 'code', 'admin_name', 'type', 'is_required', 'is_unique', 'value_per_locale', 'value_per_channel');

		$this->addFilter('is_unique', 'is_unique');
		$this->addFilter('value_per_locale', 'value_per_locale');
		$this->addFilter('value_per_channel', 'value_per_channel');

		$this->setQueryBuilder($queryBuilder);
	}

	/**
	 * Add columns.
	 *
	 * @throws \Webkul\Ui\Exceptions\ColumnKeyException add column failed
	 * @return void
	 */
    public function addColumns():void
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
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'admin_name',
            'label'      => trans('admin::app.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'type',
            'label'      => trans('admin::app.type'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'is_required',
            'label'      => trans('admin::app.required'),
            'type'       => 'boolean',
            'sortable'   => true,
            'searchable' => false,
            'closure'    => function ($value) {
				return (int) $value->is_required === 1
					? trans('admin::app.datagrid.true')
					: trans('admin::app.datagrid.false');
			},
        ]);

        $this->addColumn([
            'index'      => 'is_unique',
            'label'      => trans('admin::app.unique'),
            'type'       => 'boolean',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
			'closure' => function ($value) {
				return (int) $value->is_unique === 1
					? trans('admin::app.datagrid.true')
					: trans('admin::app.datagrid.false');
			},
		]);

        $this->addColumn([
            'index'      => 'value_per_locale',
            'label'      => trans('admin::app.locale-based'),
            'type'       => 'boolean',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
			'closure' => function ($value) {
				return (int) $value->value_per_locale === 1
					? trans('admin::app.datagrid.true')
					: trans('admin::app.datagrid.false');
			},
		]);

        $this->addColumn([
            'index'      => 'value_per_channel',
            'label'      => trans('admin::app.channel-based'),
            'type'       => 'boolean',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
            'closure'    => function ($value) {
				return (int) $value->value_per_channel === 1
					? trans('admin::app.datagrid.true')
					: trans('admin::app.datagrid.false');
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
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.catalog.attributes.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.catalog.attributes.delete',
            'icon'  => 'icon trash-icon',
        ]);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions():void
    {
        $this->addMassAction([
            'type'   => 'delete',
            'action' => route('admin.catalog.attributes.massdelete'),
            'label'  => trans('admin::app.datagrid.delete'),
            'index'  => 'admin_name',
            'method' => 'POST',
        ]);
    }
}
