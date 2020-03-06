<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class UserDataGrid extends DataGrid
{
    protected $index = 'user_id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('admins as u')
            ->leftJoin('roles as ro', 'u.role_id', '=', 'ro.id')
            ->addSelect('u.id as user_id', 'u.name as user_name', 'u.status', 'u.email', 'ro.name as role_name');

        $this->addFilter('user_id', 'u.id');
        $this->addFilter('user_name', 'u.name');
        $this->addFilter('role_name', 'ro.name');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
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
            'wrapper'    => function($value) {
                if ($value->status == 1) {
                    return 'Active';
                } else {
                    return 'Inactive';
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

        $this->addColumn([
            'index'      => 'role_name',
            'label'      => trans('admin::app.datagrid.role'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions()
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