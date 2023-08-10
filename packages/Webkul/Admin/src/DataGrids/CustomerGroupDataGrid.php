<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CustomerGroupDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('customer_groups')->addSelect('id', 'code', 'name');

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'integer',
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
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
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
            'route'  => 'admin.groups.edit',
            'url'    => function ($row) {
                return route('admin.groups.edit', $row->id);
            },
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.groups.delete',
            'url'          => function ($row) {
                return route('admin.groups.delete', $row->id);
            },
        ]);
    }
}
