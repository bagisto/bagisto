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
            ->addSelect(
                'u.id as user_id',
                'u.name as user_name',
                'u.image as user_image',
                'u.status',
                'u.email',
                'ro.name as role_name'
            );

        $this->addFilter('user_id', 'u.id');
        $this->addFilter('user_name', 'u.name');
        $this->addFilter('role_name', 'ro.name');
        $this->addFilter('status', 'u.status');

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
            'label'      => trans('admin::app.users.users.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'user_name',
            'label'      => trans('admin::app.users.users.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                if ($row->user_image) {
                    return 
                    '<div class="flex gap-[10px] items-center">
                        <div class="inline-block w-[36px] h-[36px] rounded-full border-3 border-gray-800 align-middle text-center mr-2 overflow-hidden">
                            <img class="w-[36px] h-[36px]" src="' . Storage::url($row->user_image) . '" alt="' . $row->user_name . '">
                        </div>

                        <div class="text-sm">' . $row->user_name . '</div> 
                    </div>';
                
                }

                return  
                    '<div class="flex gap-[10px] items-center">
                        <div class="inline-block w-[36px] h-[36px] rounded-full bg-gray-200 border-3 border-gray-800 align-middle text-center mr-2 overflow-hidden">
                            <span class="icon-customer text-[30px]"></span>
                        </div>

                        <div class="text-sm">' . $row->user_name . '</div>
                    </div>';
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.users.users.index.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                if ($value->status) {
                    return trans('admin::app.users.users.index.datagrid.active');
                }

                return trans('admin::app.users.users.index.datagrid.inactive');
            },
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('admin::app.users.users.index.datagrid.email'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'role_name',
            'label'      => trans('admin::app.users.users.index.datagrid.role'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
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
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.users.users.index.datagrid.edit'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.users.edit', $row->user_id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.users.users.index.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => function ($row) {
                return route('admin.users.delete', $row->user_id);
            },
        ]);
    }
}
