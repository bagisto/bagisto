<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\DataGrid\DataGrid;

class UserDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected $primaryColumn = 'user_id';

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('admins as u')
            ->leftJoin('roles as ro', 'u.role_id', '=', 'ro.id')
            ->addSelect('u.id as user_id', 'u.name as user_name', 'u.image as user_image', 'u.status', 'u.email', 'ro.name as role_name');

        // $this->addFilter('user_id', 'u.id');
        // $this->addFilter('user_name', 'u.name');
        // $this->addFilter('role_name', 'ro.name');
        // $this->addFilter('status', 'u.status');

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'user_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'integer',
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
            'closure'    => function ($row) {
                if ($row->user_image) {
                    return '<div class="avatar"><img src="' . Storage::url($row->user_image) . '"></div>' . $row->user_name;
                }

                return '<div class="avatar"><span class="icon profile-pic-icon"></span></div>' . $row->user_name;
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($value) {
                if ($value->status) {
                    return trans('admin::app.datagrid.active');
                }

                return trans('admin::app.datagrid.inactive');
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
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.users.edit',
            'url'    => function ($row) {
                return route('admin.users.edit', $row->user_id);
            },
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'DELETE',
            'route'  => 'admin.users.delete',
            'url'    => function ($row) {
                return route('admin.users.delete', $row->user_id);
            },
        ]);
    }
}
