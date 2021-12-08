<?php

namespace Webkul\Admin\DataGrids;

use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\Ui\DataGrid\DataGrid;

class CartRuleCouponDataGrid extends DataGrid
{
    protected string $index = 'id';

    protected string $sortOrder = 'desc';

    public function prepareQueryBuilder(): void
	{
		$route = request()->route() ? request()->route()->getName() : "";

		$cartRuleId = ($route === 'admin.cart-rules.edit' ? collect(request()->segments())->last() : last(explode("/", url()->previous())));

		$queryBuilder = CartRuleCoupon::query()
                ->addSelect('id', 'code', 'created_at', 'expired_at', 'times_used')
                ->where('cart_rule_coupons.cart_rule_id', $cartRuleId);

        $this->setQueryBuilder($queryBuilder);
    }

	/**
	 * @throws \Webkul\Ui\Exceptions\ColumnKeyException add column failed
	 */
	public function addColumns(): void
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

    public function prepareMassActions(): void
    {
        $this->addMassAction([
            'type'   => 'delete',
            'action' => route('admin.cart-rule-coupons.mass-delete'),
            'label'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
        ]);
    }
}
