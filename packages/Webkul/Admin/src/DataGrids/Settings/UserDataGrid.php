<?php

namespace Webkul\Admin\DataGrids\Settings;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\DataGrid\DataGrid;
use Webkul\User\Repositories\RoleRepository;

class UserDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected $primaryColumn = 'user_id';

    /**
     * Constructor for the class.
     *
     * @return void
     */
    public function __construct(protected RoleRepository $roleRepository) {}

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('admins')
            ->leftJoin('roles', 'admins.role_id', '=', 'roles.id')
            ->select(
                'admins.id as user_id',
                'admins.name as user_name',
                'admins.image as user_image',
                'admins.status',
                'admins.email',
                'roles.name as role_name'
            );

        $this->addFilter('user_id', 'admins.id');
        $this->addFilter('user_name', 'admins.name');
        $this->addFilter('role_name', 'roles.name');
        $this->addFilter('status', 'admins.status');

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
            'label'      => trans('admin::app.settings.users.index.datagrid.id'),
            'type'       => 'integer',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'user_name',
            'label'      => trans('admin::app.settings.users.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'user_img',
            'label'      => trans('admin::app.settings.users.index.datagrid.name'),
            'type'       => 'string',
            'closure'    => function ($row) {
                if ($row->user_image) {
                    return Storage::url($row->user_image);
                }

                return null;
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.settings.users.index.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                if ($value->status) {
                    return trans('admin::app.settings.users.index.datagrid.active');
                }

                return trans('admin::app.settings.users.index.datagrid.inactive');
            },
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('admin::app.settings.users.index.datagrid.email'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'role_name',
            'label'              => trans('admin::app.settings.users.index.datagrid.role'),
            'type'               => 'string',
            'searchable'         => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => $this->roleRepository->all(['name as label', 'name as value'])->toArray(),
            'sortable'           => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('settings.users.users.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.settings.users.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.settings.users.edit', $row->user_id);
                },
            ]);
        }

        if (bouncer()->hasPermission('settings.users.users.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.settings.users.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.settings.users.delete', $row->user_id);
                },
            ]);
        }
    }
}
