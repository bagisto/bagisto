<?php

namespace Webkul\Shop\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * OrderDataGrid class
 *
 * @author Rahul Shukla <rahulshkla.symfont517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderDataGrid extends DataGrid
{
    protected $index = 'id'; //the column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('orders as order')
                ->addSelect('order.id', 'order.increment_id', 'order.status', 'order.created_at', 'order.grand_total', 'order.order_currency_code')
                ->where('customer_id', auth()->guard('customer')->user()->id);

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'increment_id',
            'label' => trans('shop::app.customer.account.order.index.order_id'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('shop::app.customer.account.order.index.date'),
            'type' => 'datetime',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'grand_total',
            'label' => trans('shop::app.customer.account.order.index.total'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function ($value) {
                return core()->formatPrice($value->grand_total, $value->order_currency_code);
            }
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('shop::app.customer.account.order.index.status'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'closure' => true,
            'wrapper' => function ($value) {
                if ($value->status == 'processing')
                    return '<span class="badge badge-md badge-success">Processing</span>';
                else if ($value->status == 'completed')
                    return '<span class="badge badge-md badge-success">Completed</span>';
                else if ($value->status == "canceled")
                    return '<span class="badge badge-md badge-danger">Canceled</span>';
                else if ($value->status == "closed")
                    return '<span class="badge badge-md badge-info">Closed</span>';
                else if ($value->status == "pending")
                    return '<span class="badge badge-md badge-warning">Pending</span>';
                else if ($value->status == "pending_payment")
                    return '<span class="badge badge-md badge-warning">Pending Payment</span>';
                else if ($value->status == "fraud")
                    return '<span class="badge badge-md badge-danger">Fraud</span>';
            },
            'filterable' => true
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'View',
            'method' => 'GET',
            'route' => 'customer.orders.view',
            'icon' => 'icon eye-icon'
        ]);
    }
}