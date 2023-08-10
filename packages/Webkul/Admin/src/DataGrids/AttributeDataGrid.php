<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class AttributeDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('attributes')
            ->select('id')
            ->addSelect('id', 'code', 'admin_name', 'type', 'is_required', 'is_unique', 'value_per_locale', 'value_per_channel', 'created_at');

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
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('admin::app.datagrid.code'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'admin_name',
            'label'      => trans('admin::app.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'type',
            'label'      => trans('admin::app.type'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'is_required',
            'label'      => trans('admin::app.required'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'is_unique',
            'label'      => trans('admin::app.unique'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'value_per_locale',
            'label'      => trans('admin::app.locale-based'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'value_per_channel',
            'label'      => trans('admin::app.channel-based'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('Created At'),
            'type'       => 'date_range',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('Created At'),
            'type'       => 'datetime_range',
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
            'icon'   => 'icon pencil-lg-icon',
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.catalog.attributes.edit',
            'url'    => function ($row) {
                return route('admin.catalog.attributes.edit', $row->id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon trash-icon',
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.catalog.attributes.delete',
            'url'    => function ($row) {
                return route('admin.catalog.attributes.delete', $row->id);
            },
        ]);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'   => 'delete',
            'action' => route('admin.catalog.attributes.mass_delete'),
            'label'  => trans('admin::app.datagrid.delete'),
            'index'  => 'admin_name',
            'method' => 'POST',
        ]);
    }
}
