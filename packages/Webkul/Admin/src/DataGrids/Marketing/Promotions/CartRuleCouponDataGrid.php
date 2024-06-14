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
            ->select(
                'id',
                'code',
                'created_at',
                'expired_at',
                'times_used'
            )
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
            'label'      => trans('admin::app.marketing.promotions.cart-rules-coupons.datagrid.id'),
            'type'       => 'integer',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('admin::app.marketing.promotions.cart-rules-coupons.datagrid.coupon-code'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('admin::app.marketing.promotions.cart-rules-coupons.datagrid.created-date'),
            'type'            => 'datetime',
            'filterable'      => true,
            'filterable_type' => 'datetime_range',
            'sortable'        => true,
        ]);

        $this->addColumn([
            'index'           => 'expired_at',
            'label'           => trans('admin::app.marketing.promotions.cart-rules-coupons.datagrid.expiration-date'),
            'type'            => 'datetime',
            'filterable'      => true,
            'filterable_type' => 'datetime_range',
            'sortable'        => true,
        ]);

        $this->addColumn([
            'index'      => 'times_used',
            'label'      => trans('admin::app.marketing.promotions.cart-rules-coupons.datagrid.times-used'),
            'type'       => 'integer',
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
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.marketing.promotions.catalog-rules.index.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => function ($row) {
                return route('admin.marketing.promotions.cart_rules.coupons.delete', $row->id);
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
            'title'  => trans('admin::app.marketing.promotions.cart-rules-coupons.datagrid.delete'),
            'method' => 'POST',
            'url'    => route('admin.marketing.promotions.cart_rules.coupons.mass_delete'),
        ]);
    }
}
