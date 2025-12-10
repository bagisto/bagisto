<?php

namespace Webkul\Admin\DataGrids\Sales\RMA;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CustomFieldDataGrid extends DataGrid
{
    /**
     * @var int
     */
    public const ONE = 1;

    /**
     * @var int
     */
    public const ZERO = 0;

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

        $this->addFilter('code', 'rma_custom_fields.code');

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.catalog.attributes.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('admin::app.catalog.attributes.index.datagrid.code'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'label',
            'label'      => trans('admin::app.catalog.attributes.create.label'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'type',
            'label'      => trans('admin::app.catalog.attributes.index.datagrid.type'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'              => 'is_required',
            'label'              => trans('admin::app.catalog.attributes.index.datagrid.required'),
            'type'               => 'string',
            'searchable'         => false,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.catalog.products.edit.types.bundle.update-create.yes'),
                    'value' => self::ONE,
                ], [
                    'label' => trans('admin::app.catalog.products.edit.types.bundle.update-create.no'),
                    'value' => self::ZERO,
                ],
            ],
            'closure'            => function ($row) {
                if ($row->is_required == self::ONE) {
                    return '<span class="label-active">'.trans('admin::app.catalog.products.edit.types.bundle.update-create.yes').'</span>';
                }

                return '<span class="label-info">'.trans('admin::app.catalog.products.edit.types.bundle.update-create.no').'</span>';
            },
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('admin::app.rma.sales.rma.reasons.index.datagrid.status'),
            'type'               => 'string',
            'searchable'         => true,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.rma.sales.rma.reasons.index.datagrid.enabled'),
                    'value' => self::ONE,
                ], [
                    'label' => trans('admin::app.rma.sales.rma.reasons.index.datagrid.disabled'),
                    'value' => self::ZERO,
                ],
            ],
            'closure'    => function ($row) {
                if ($row->status) {
                    return '<span class="label-active">'.trans('admin::app.catalog.categories.index.datagrid.active').'</span>';
                }

                return '<span class="label-info">'.trans('admin::app.catalog.categories.index.datagrid.inactive').'</span>';
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
                'title'  => trans('admin::app.catalog.categories.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.rma.custom-field.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('sales.custom-field.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.catalog.categories.index.datagrid.delete'),
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
                'title'   => trans('shop::app.rma.customer-rma-index.update'),
                'method'  => 'POST',
                'url'     => route('admin.sales.rma.custom-field.mass-update'),
                'options' => [
                    [
                        'label' => trans('admin::app.rma.sales.rma.reasons.index.datagrid.enabled'),
                        'value' => self::ONE,
                    ], [
                        'label' => trans('admin::app.rma.sales.rma.reasons.index.datagrid.disabled'),
                        'value' => self::ZERO,
                    ],
                ],
            ]);
        }

        if (bouncer()->hasPermission('sales.custom-field.delete')) {
            $this->addMassAction([
                'title'  => trans('shop::app.rma.customer-rma-index.delete'),
                'method' => 'POST',
                'url'    => route('admin.sales.rma.custom-field.mass-delete'),
            ]);
        }
    }
}
