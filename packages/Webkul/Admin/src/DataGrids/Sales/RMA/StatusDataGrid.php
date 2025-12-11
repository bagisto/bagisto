<?php

namespace Webkul\Admin\DataGrids\Sales\RMA;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\RMA\Repositories\RMAStatusRepository;

class StatusDataGrid extends DataGrid
{
    /**
     * Constructor for the class.
     *
     * @return void
     */
    public function __construct(
        protected RMAStatusRepository $rmaStatusRepository,
    ) {}

    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('rma_statuses')
            ->addSelect(
                'id',
                'title',
                'color',
                'status',
                'default',
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
            'label'      => trans('admin::app.sales.rma.reasons.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('admin::app.customers.reviews.index.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'color',
            'label'              => trans('admin::app.catalog.attributes.create.color'),
            'type'               => 'string',
            'searchable'         => true,
            'filterable'         => true,
            'sortable'           => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => $this->rmaStatusRepository->all(['color as label', 'color as value'])->toArray(),
            'closure'            => function ($row) {
                if ($row->color) {
                    return '<p class="label-active" style="background: '.$row->color.';">'.$row->color.'</p>';
                }
            },
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
            'closure'            => function ($row) {
                if ($row->status) {
                    return '<p class="label-active">'.trans('admin::app.sales.rma.reasons.index.datagrid.enabled').'</p>';
                }

                return '<p class="label-canceled">'.trans('admin::app.sales.rma.reasons.index.datagrid.disabled').'</p>';
            },
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('sales.rma-status.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('shop::app.rma.customer-rma-index.edit'),
                'type'   => 'Edit',
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.rma.rma-status.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('sales.rma-status.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('shop::app.rma.customer-rma-index.delete'),
                'type'   => 'Delete',
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.sales.rma.rma-status.delete', $row->id);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        if (bouncer()->hasPermission('sales.rma-status.edit')) {
            $this->addMassAction([
                'title'   => trans('shop::app.rma.customer-rma-index.update'),
                'method'  => 'POST',
                'url'     => route('admin.sales.rma.rma-status.mass-update'),
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

        if (bouncer()->hasPermission('sales.rma-status.delete')) {
            $this->addMassAction([
                'title'  => trans('shop::app.rma.customer-rma-index.delete'),
                'method' => 'POST',
                'url'    => route('admin.sales.rma.rma-status.mass-delete'),
            ]);
        }
    }
}
