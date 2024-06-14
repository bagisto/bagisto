<?php

namespace Webkul\Admin\DataGrids\Marketing\Promotions;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CartRuleDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('cart_rules')
            ->distinct()
            ->leftJoin('cart_rule_coupons', function ($leftJoin) {
                $leftJoin->on('cart_rule_coupons.cart_rule_id', '=', 'cart_rules.id')
                    ->where('cart_rule_coupons.is_primary', 1);
            })
            ->select(
                'cart_rules.id',
                'name',
                'cart_rule_coupons.code as coupon_code',
                'status',
                'starts_from',
                'ends_till',
                'sort_order'
            );

        $this->addFilter('id', 'cart_rules.id');
        $this->addFilter('coupon_code', 'cart_rule_coupons.code');

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
            'label'      => trans('admin::app.marketing.promotions.cart-rules.index.datagrid.id'),
            'type'       => 'integer',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.marketing.promotions.cart-rules.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'coupon_code',
            'label'      => trans('admin::app.marketing.promotions.cart-rules.index.datagrid.coupon-code'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                return $value->coupon_code ?? '-';
            },
        ]);

        $this->addColumn([
            'index'           => 'starts_from',
            'label'           => trans('admin::app.marketing.promotions.cart-rules.index.datagrid.start'),
            'type'            => 'datetime',
            'filterable'      => true,
            'filterable_type' => 'datetime_range',
            'sortable'        => true,
            'closure'         => function ($value) {
                return $value->starts_from ?? '-';
            },
        ]);

        $this->addColumn([
            'index'           => 'ends_till',
            'label'           => trans('admin::app.marketing.promotions.cart-rules.index.datagrid.end'),
            'type'            => 'datetime',
            'filterable'      => true,
            'filterable_type' => 'datetime_range',
            'sortable'        => true,
            'closure'         => function ($value) {
                return $value->ends_till ?? '-';
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.marketing.promotions.cart-rules.index.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                if ($value->status == 1) {
                    return trans('admin::app.marketing.promotions.cart-rules.index.datagrid.active');
                } elseif ($value->status == 0) {
                    return trans('admin::app.marketing.promotions.cart-rules.index.datagrid.inactive');
                }

                return trans('admin::app.marketing.promotions.cart-rules.index.datagrid.draft');
            },
        ]);

        $this->addColumn([
            'index'      => 'sort_order',
            'label'      => trans('admin::app.marketing.promotions.cart-rules.index.datagrid.priority'),
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
        if (bouncer()->hasPermission('marketing.promotions.cart_rules.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.marketing.promotions.cart-rules.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.marketing.promotions.cart_rules.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('marketing.promotions.cart_rules.copy')) {
            $this->addAction([
                'icon'   => 'icon-copy',
                'title'  => trans('admin::app.marketing.promotions.cart-rules.index.datagrid.copy'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.marketing.promotions.cart_rules.copy', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('marketing.promotions.cart_rules.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.marketing.promotions.cart-rules.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.marketing.promotions.cart_rules.delete', $row->id);
                },
            ]);
        }
    }
}
