<?php

namespace Webkul\Admin\DataGrids\Marketing\Promotions;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CartRuleCouponDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('cart_rule_coupons')
            ->addSelect('id', 'code', 'created_at', 'expired_at', 'times_used')
            ->where('cart_rule_coupons.cart_rule_id', request('id'));

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
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('admin::app.datagrid.coupon-code'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.datagrid.created-date'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'expired_at',
            'label'      => trans('admin::app.datagrid.expiration-date'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'times_used',
            'label'      => trans('admin::app.datagrid.times-used'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
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
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'url'    => route('admin.marketing.promotions.cart_rules.coupons.mass_delete'),
        ]);
    }
}
