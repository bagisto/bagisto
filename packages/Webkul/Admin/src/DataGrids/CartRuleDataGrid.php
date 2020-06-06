<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class CartRuleDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('cart_rules')
            ->leftJoin('cart_rule_coupons', function($leftJoin) {
                $leftJoin->on('cart_rule_coupons.cart_rule_id', '=', 'cart_rules.id')
                         ->where('cart_rule_coupons.is_primary', 1);
            })
            ->addSelect('cart_rules.id', 'name', 'cart_rule_coupons.code as coupon_code', 'status', 'starts_from', 'ends_till', 'sort_order');

        $this->addFilter('id', 'cart_rules.id');
        $this->addFilter('coupon_code', 'cart_rule_coupons.code');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'coupon_code',
            'label'      => trans('admin::app.datagrid.coupon-code'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'starts_from',
            'label'      => trans('admin::app.datagrid.start'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'ends_till',
            'label'      => trans('admin::app.datagrid.end'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.status'),
            'type'       => 'boolean',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'wrapper'    => function($value) {
                if ($value->status == 1) {
                    return trans('admin::app.datagrid.active');
                } else {
                    return trans('admin::app.datagrid.inactive');
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'sort_order',
            'label'      => trans('admin::app.datagrid.priority'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.cart-rules.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.cart-rules.delete',
            'icon'   => 'icon trash-icon',
        ]);
    }
}
