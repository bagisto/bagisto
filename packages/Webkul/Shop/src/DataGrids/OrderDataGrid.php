<?php

namespace Webkul\Shop\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class OrderDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('orders')
            ->addSelect(
                'orders.id',
                'orders.increment_id',
                'orders.status',
                'orders.created_at',
                'orders.grand_total',
                'orders.order_currency_code'
            )
            ->where('customer_id', auth()->guard('customer')->user()->id);

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
            'index'      => 'increment_id',
            'label'      => trans('shop::app.customers.account.orders.order-id'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('shop::app.customers.account.orders.order-date'),
            'type'       => 'datetime_range',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'grand_total',
            'label'      => trans('shop::app.customers.account.orders.total'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                return core()->formatPrice($row->grand_total, $row->order_currency_code);
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('shop::app.customers.account.orders.status.title'),
            'type'       => 'checkbox',
            'options'    => [
                [
                    'name'  => 'processing',
                    'value' => trans('shop::app.customers.account.orders.status.options.processing'),
                ],
                [
                    'name'  => 'completed',
                    'value' => trans('shop::app.customers.account.orders.status.options.completed'),
                ],
                [
                    'name'  => 'canceled',
                    'value' => trans('shop::app.customers.account.orders.status.options.canceled'),
                ],
                [
                    'name'  => 'closed',
                    'value' => trans('shop::app.customers.account.orders.status.options.closed'),
                ],
                [
                    'name'  => 'pending',
                    'value' => trans('shop::app.customers.account.orders.status.options.pending'),
                ],
                [
                    'name'  => 'pending_payment',
                    'value' => trans('shop::app.customers.account.orders.status.options.pending-payment'),
                ],
                [
                    'name'  => 'fraud',
                    'value' => trans('shop::app.customers.account.orders.status.options.fraud'),
                ],
            ],
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                if ($row->status == 'processing') {
                    return '<span class="badge badge-md badge-success">' . trans('shop::app.customers.account.orders.status.options.processing') . '</span>';
                } elseif ($row->status == 'completed') {
                    return '<span class="badge badge-md badge-success">' . trans('shop::app.customers.account.orders.status.options.completed') . '</span>';
                } elseif ($row->status == 'canceled') {
                    return '<span class="badge badge-md badge-danger">' . trans('shop::app.customers.account.orders.status.options.canceled') . '</span>';
                } elseif ($row->status == 'closed') {
                    return '<span class="badge badge-md badge-info">' . trans('shop::app.customers.account.orders.status.options.closed') . '</span>';
                } elseif ($row->status == 'pending') {
                    return '<span class="badge badge-md badge-warning">' . trans('shop::app.customers.account.orders.status.options.pending') . '</span>';
                } elseif ($row->status == 'pending_payment') {
                    return '<span class="badge badge-md badge-warning">' . trans('shop::app.customers.account.orders.status.options.pending-payment') . '</span>';
                } elseif ($row->status == 'fraud') {
                    return '<span class="badge badge-md badge-danger">' . trans('shop::app.customers.account.orders.status.options.fraud') . '</span>';
                }
            },
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
            'icon'   => 'icon-eye',
            'title'  => trans('ui::app.datagrid.view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('shop.customers.account.orders.view', $row->id);
            },
        ]);
    }
}
