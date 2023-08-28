<?php

namespace Webkul\Admin\DataGrids\Customers;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class GroupDataGrid extends DataGrid
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
            'label'      => trans('admin::app.customers.groups.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('admin::app.customers.groups.index.datagrid.code'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.customers.groups.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.customers.groups.index.datagrid.edit'),
            'method' => 'GET',
            'url'    => function ($row) {
                // return route('admin.groups.edit', $row->id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.customers.groups.index.datagrid.delete'),
            'method' => 'POST',
            'url'    => function ($row) {
                return route('admin.customers.groups.delete', $row->id);
            },
        ]);
    }
}
