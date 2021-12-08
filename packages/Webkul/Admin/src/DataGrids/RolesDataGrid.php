<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Webkul\User\Models\Role;

class RolesDataGrid extends DataGrid
{
    protected string $index = 'id';

    protected string $sortOrder = 'desc';

    public function prepareQueryBuilder(): void
    {
		$queryBuilder = Role::select('id', 'name', 'permission_type');

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
            'width'      => '40px',
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'permission_type',
            'label'      => trans('admin::app.datagrid.permission-type'),
            'type'       => 'string',
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
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.roles.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.roles.delete',
            'icon'   => 'icon trash-icon',
        ]);
    }
}
