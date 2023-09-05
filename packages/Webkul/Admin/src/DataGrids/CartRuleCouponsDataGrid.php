<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CartRuleCouponsDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('cart_rules')
            ->select('id')
            ->addSelect(
                'id',
                'code',
                'limit',
                'usage_per_customer',
                'usage_throttle'
            );

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
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('admin::app.datagrid.code'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'limit',
            'label'      => trans('admin::app.datagrid.limit'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'limit',
            'label'      => trans('admin::app.datagrid.limit'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'usage_per_customer',
            'label'      => trans('admin::app.datagrid.usage-per-customer'),
            'type'       => 'boolean',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                if ($value->end_other_rules) {
                    return trans('admin::app.datagrid.true');
                }

                return trans('admin::app.datagrid.false');
            },
        ]);
    }
}
