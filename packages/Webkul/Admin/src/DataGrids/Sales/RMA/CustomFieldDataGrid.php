<?php

namespace Webkul\Admin\DataGrids\Sales\RMA;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CustomFieldDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('rma_custom_fields')
            ->addSelect(
                'id',
                'code',
                'label',
                'type',
                'is_required',
                'position',
                'status',
            );

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.sales.rma.custom-field.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('admin::app.sales.rma.custom-field.index.datagrid.code'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'label',
            'label'      => trans('admin::app.sales.rma.custom-field.index.datagrid.label'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'type',
            'label'      => trans('admin::app.sales.rma.custom-field.index.datagrid.type'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'              => 'is_required',
            'label'              => trans('admin::app.sales.rma.custom-field.index.datagrid.required'),
            'type'               => 'string',
            'searchable'         => false,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.sales.rma.custom-field.index.datagrid.yes'),
                    'value' => 1,
                ], [
                    'label' => trans('admin::app.sales.rma.custom-field.index.datagrid.no'),
                    'value' => 0,
                ],
            ],
            'closure'            => function ($row) {
                if ($row->is_required) {
                    return '<span class="label-active">'.trans('admin::app.sales.rma.custom-field.index.datagrid.yes').'</span>';
                }

                return '<span class="label-info">'.trans('admin::app.sales.rma.custom-field.index.datagrid.no').'</span>';
            },
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('admin::app.sales.rma.custom-field.index.datagrid.status'),
            'type'               => 'string',
            'searchable'         => true,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.sales.rma.custom-field.index.datagrid.enabled'),
                    'value' => 1,
                ], [
                    'label' => trans('admin::app.sales.rma.custom-field.index.datagrid.disabled'),
                    'value' => 0,
                ],
            ],
            'closure'    => function ($row) {
                if ($row->status) {
                    return '<span class="label-active">'.trans('admin::app.sales.rma.custom-field.index.datagrid.enabled').'</span>';
                }

                return '<span class="label-info">'.trans('admin::app.sales.rma.custom-field.index.datagrid.disabled').'</span>';
            },
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('sales.custom-field.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.sales.rma.custom-field.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.rma.custom-field.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('sales.custom-field.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.sales.rma.custom-field.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.sales.rma.custom-field.delete', $row->id);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        if (bouncer()->hasPermission('sales.custom-field.edit')) {
            $this->addMassAction([
                'title'   => trans('admin::app.sales.rma.custom-field.index.datagrid.update'),
                'method'  => 'POST',
                'url'     => route('admin.sales.rma.custom-field.mass-update'),
                'options' => [
                    [
                        'label' => trans('admin::app.sales.rma.custom-field.index.datagrid.enabled'),
                        'value' => 1,
                    ], [
                        'label' => trans('admin::app.sales.rma.custom-field.index.datagrid.disabled'),
                        'value' => 0,
                    ],
                ],
            ]);
        }

        if (bouncer()->hasPermission('sales.custom-field.delete')) {
            $this->addMassAction([
                'title'  => trans('admin::app.sales.rma.custom-field.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.sales.rma.custom-field.mass-delete'),
            ]);
        }
    }
}
