<?php

namespace Webkul\Admin\DataGrids\Sales\RMA;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class RulesDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('rma_rules')
            ->addSelect(
                'id',
                'name',
                'status',
                'return_period',
            );

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.sales.rma.rules.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.sales.rma.rules.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('admin::app.sales.rma.rules.index.datagrid.status'),
            'type'               => 'string',
            'searchable'         => false,
            'filterable'         => true,
            'sortable'           => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.sales.rma.rules.index.datagrid.enabled'),
                    'value' => 1,
                ], [
                    'label' => trans('admin::app.sales.rma.rules.index.datagrid.disabled'),
                    'value' => 0,
                ],
            ],
            'closure'            => function ($row) {
                if ($row->status) {
                    return '<p class="label-active">'.trans('admin::app.sales.rma.rules.index.datagrid.enabled').'</p>';
                }

                return '<p class="label-canceled">'.trans('admin::app.sales.rma.rules.index.datagrid.disabled').'</p>';
            },
        ]);

        $this->addColumn([
            'index'      => 'return_period',
            'label'      => trans('admin::app.sales.rma.rules.index.datagrid.return-period'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('sales.rma-rules.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.sales.rma.rules.index.datagrid.edit'),
                'type'   => 'Edit',
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.rma.rules.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('sales.rma-rules.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.sales.rma.rules.index.datagrid.delete'),
                'type'   => 'Delete',
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.sales.rma.rules.delete', $row->id);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        if (bouncer()->hasPermission('sales.rma-rules.edit')) {
            $this->addMassAction([
                'title'   => trans('admin::app.sales.rma.rules.index.datagrid.update'),
                'method'  => 'POST',
                'url'     => route('admin.sales.rma.rules.mass-update'),
                'options' => [
                    [
                        'label' => trans('admin::app.sales.rma.rules.index.datagrid.enabled'),
                        'value' => 1,
                    ], [
                        'label' => trans('admin::app.sales.rma.rules.index.datagrid.disabled'),
                        'value' => 0,
                    ],
                ],
            ]);
        }

        if (bouncer()->hasPermission('sales.rma-rules.delete')) {
            $this->addMassAction([
                'title'  => trans('admin::app.sales.rma.rules.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.sales.rma.rules.mass-delete'),
            ]);
        }
    }
}
