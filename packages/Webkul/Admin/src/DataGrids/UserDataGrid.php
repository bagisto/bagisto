<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Webkul\User\Models\Admin;

class UserDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected string $index = 'user_id';

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
		$queryBuilder = Admin::from('admins as u')
			->leftJoin('roles as ro', 'u.role_id', '=', 'ro.id')
			->addSelect('u.id as user_id', 'u.name as user_name', 'u.status', 'u.email', 'ro.name as role_name');

        $this->addFilter('user_id', 'u.id');
        $this->addFilter('user_name', 'u.name');
        $this->addFilter('role_name', 'ro.name');
        $this->addFilter('status', 'u.status');

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
            'index'      => 'user_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'user_name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

		$this->addColumn([
			'index'      => 'status',
			'label'      => trans('admin::app.datagrid.status'),
			'type'       => 'boolean',
			'searchable' => true,
			'sortable'   => true,
			'filterable' => true,
			'closure'    => function ($value) {
				return (int) $value->status === 1
					? trans('admin::app.datagrid.active')
					: trans('admin::app.datagrid.inactive');
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

        $this->addColumn([
            'index'      => 'role_name',
            'label'      => trans('admin::app.datagrid.role'),
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
            'route'  => 'admin.users.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.users.delete',
            'icon'   => 'icon trash-icon',
        ]);
    }
}
