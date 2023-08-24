<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class EventDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('marketing_events')->addSelect('id', 'name', 'date');

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.marketing.email-marketing.events.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.marketing.email-marketing.events.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'date',
            'label'      => trans('admin::app.marketing.email-marketing.events.index.datagrid.date'),
            'type'       => 'datetime',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.marketing.email-marketing.events.index.datagrid.edit'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.events.edit', $row->id);
            },
        ]);

        $this->addAction([
            'icon'    => 'icon-delete',
            'title'   => trans('admin::app.marketing.email-marketing.events.index.datagrid.delete'),
            'method'  => 'POST',
            'url'     => function ($row) {
                return route('admin.events.delete', $row->id);
            },
        ]);
    }
}
