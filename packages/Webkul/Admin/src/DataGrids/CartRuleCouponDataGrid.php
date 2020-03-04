<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * CartRuleCouponDataGrid class
 *
 * @author  Jitendra Singh <jitendra@webkul.com>
 * @copyright  2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartRuleCouponDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('cart_rule_coupons')
                ->addSelect('id', 'code', 'created_at', 'expired_at', 'times_used')
                ->where('cart_rule_coupons.cart_rule_id', collect(request()->segments())->last());

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
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'   => 'delete',
            'action' => route('admin.cart-rule-coupons.mass-delete'),
            'label'  => trans('admin::app.datagrid.delete'),
            'method' => 'DELETE',
        ]);
    }
}
