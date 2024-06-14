<?php

namespace Webkul\Admin\DataGrids\Catalog;

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
        return DB::table('attributes')
            ->select(
                'id',
                'code',
                'admin_name',
                'type',
                'is_required',
                'is_unique',
                'value_per_locale',
                'value_per_channel',
                'created_at'
            );
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
            'label'      => trans('admin::app.catalog.attributes.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('admin::app.catalog.attributes.index.datagrid.code'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'admin_name',
            'label'      => trans('admin::app.catalog.attributes.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'type',
            'label'              => trans('admin::app.catalog.attributes.index.datagrid.type'),
            'type'               => 'string',
            'searchable'         => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.catalog.attributes.index.datagrid.text'),
                    'value' => 'text',
                ],
                [
                    'label' => trans('admin::app.catalog.attributes.index.datagrid.textarea'),
                    'value' => 'textarea',
                ],
                [
                    'label' => trans('admin::app.catalog.attributes.index.datagrid.price'),
                    'value' => 'price',
                ],
                [
                    'label' => trans('admin::app.catalog.attributes.index.datagrid.boolean'),
                    'value' => 'boolean',
                ],
                [
                    'label' => trans('admin::app.catalog.attributes.index.datagrid.select'),
                    'value' => 'select',
                ],
                [
                    'label' => trans('admin::app.catalog.attributes.index.datagrid.multiselect'),
                    'value' => 'multiselect',
                ],
                [
                    'label' => trans('admin::app.catalog.attributes.index.datagrid.date-time'),
                    'value' => 'datetime',
                ],
                [
                    'label' => trans('admin::app.catalog.attributes.index.datagrid.date'),
                    'value' => 'date',
                ],
                [
                    'label' => trans('admin::app.catalog.attributes.index.datagrid.image'),
                    'value' => 'image',
                ],
                [
                    'label' => trans('admin::app.catalog.attributes.index.datagrid.file'),
                    'value' => 'file',
                ],
                [
                    'label' => trans('admin::app.catalog.attributes.index.datagrid.checkbox'),
                    'value' => 'checkbox',
                ],
            ],
            'sortable' => true,
        ]);

        $this->addColumn([
            'index'      => 'is_required',
            'label'      => trans('admin::app.catalog.attributes.index.datagrid.required'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'is_unique',
            'label'      => trans('admin::app.catalog.attributes.index.datagrid.unique'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'value_per_locale',
            'label'      => trans('admin::app.catalog.attributes.index.datagrid.locale-based'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'value_per_channel',
            'label'      => trans('admin::app.catalog.attributes.index.datagrid.channel-based'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('admin::app.catalog.attributes.index.datagrid.created-at'),
            'type'            => 'date',
            'searchable'      => true,
            'filterable'      => true,
            'filterable_type' => 'date_range',
            'sortable'        => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('catalog.attributes.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.catalog.attributes.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.catalog.attributes.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('catalog.attributes.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.catalog.attributes.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.catalog.attributes.delete', $row->id);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('catalog.attributes.delete')) {
            $this->addMassAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.catalog.attributes.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.catalog.attributes.mass_delete'),
            ]);
        }
    }
}
