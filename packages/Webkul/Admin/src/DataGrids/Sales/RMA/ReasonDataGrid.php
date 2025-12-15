<?php

namespace Webkul\Admin\DataGrids\Sales\RMA;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ReasonDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('rma_reasons')
            ->addSelect(
                'rma_reasons.id',
                'rma_reasons.title',
                'rma_reasons.status',
                'rma_reasons.position',
                'rma_reasons.created_at',
                DB::raw('GROUP_CONCAT('.DB::getTablePrefix().'rma_reason_resolutions.resolution_type SEPARATOR ", ") as resolution_types'),
            )
            ->leftJoin('rma_reason_resolutions', 'rma_reasons.id', '=', 'rma_reason_resolutions.rma_reason_id')
            ->groupBy('rma_reasons.id');

        $this->addFilter('id', 'rma_reasons.id');
        $this->addFilter('created_at', 'rma_reasons.created_at');
        $this->addFilter('resolution_types', DB::raw('GROUP_CONCAT('.DB::getTablePrefix().'rma_reason_resolutions.resolution_type SEPARATOR ", ")'));

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.sales.rma.reasons.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('admin::app.sales.rma.reasons.index.datagrid.reason'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('admin::app.sales.rma.reasons.index.datagrid.status'),
            'type'               => 'string',
            'searchable'         => false,
            'filterable'         => true,
            'sortable'           => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.sales.rma.reasons.index.datagrid.enabled'),
                    'value' => 1,
                ], [
                    'label' => trans('admin::app.sales.rma.reasons.index.datagrid.disabled'),
                    'value' => 0,
                ],
            ],
            'closure'           => function ($row) {
                if ($row->status) {
                    return '<p class="label-active">'.trans('admin::app.sales.rma.reasons.index.datagrid.enabled').'</p>';
                }

                return '<p class="label-canceled">'.trans('admin::app.sales.rma.reasons.index.datagrid.disabled').'</p>';
            },
        ]);

        $this->addColumn([
            'index'      => 'resolution_types',
            'label'      => trans('admin::app.configuration.index.sales.rma.resolution-type'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
            'closure'    => function ($row) {
                if ($row->resolution_types) {
                    return ucwords($row->resolution_types);
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'position',
            'label'      => trans('admin::app.catalog.categories.create.position'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('admin::app.sales.rma.reasons.index.datagrid.created-at'),
            'type'            => 'date',
            'searchable'      => true,
            'filterable'      => true,
            'sortable'        => true,
            'filterable_type' => 'date_range',
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('sales.rma-reason.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.sales.rma.reasons.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.rma.reason.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('sales.rma-reason.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.sales.rma.reasons.index.datagrid.delete'),
                'type'   => 'Delete',
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.sales.rma.reason.delete', $row->id);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        if (bouncer()->hasPermission('sales.rma-reason.edit')) {
            $this->addMassAction([
                'title'   => trans('admin::app.sales.rma.reasons.index.datagrid.update'),
                'method'  => 'POST',
                'url'     => route('admin.sales.rma.reason.mass-update'),
                'options' => [
                    [
                        'label' => trans('admin::app.sales.rma.reasons.index.datagrid.enabled'),
                        'value' => 1,
                    ], [
                        'label' => trans('admin::app.sales.rma.reasons.index.datagrid.disabled'),
                        'value' => 0,
                    ],
                ],
            ]);
        }

        if (bouncer()->hasPermission('sales.rma-reason.delete')) {
            $this->addMassAction([
                'title'  => trans('admin::app.sales.rma.reasons.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.sales.rma.reason.mass-delete'),
            ]);
        }
    }
}
