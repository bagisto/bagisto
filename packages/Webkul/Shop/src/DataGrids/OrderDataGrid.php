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
            'type'       => 'date_range',
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
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => [
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.processing'),
                            'value'  => 'processing',
                        ],
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.completed'),
                            'value'  => 'completed',
                        ],
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.canceled'),
                            'value'  => 'canceled',
                        ],
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.closed'),
                            'value'  => 'closed',
                        ],
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.pending'),
                            'value'  => 'pending',
                        ],
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.pending-payment'),
                            'value'  => 'pending_payment',
                        ],
                        [
                            'label'  => trans('shop::app.customers.account.orders.status.options.fraud'),
                            'value'  => 'fraud',
                        ],
                    ],
                ],
            ],
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case 'processing':
                        return '<p class="label-processing">' . trans('shop::app.customers.account.orders.status.options.processing') . '</p>';

                    case 'completed':
                        return '<p class="label-active">' . trans('shop::app.customers.account.orders.status.options.completed') . '</p>';

                    case 'canceled':
                        return '<p class="label-canceled">' . trans('shop::app.customers.account.orders.status.options.canceled') . '</p>';

                    case 'closed':
                        return '<p class="label-closed">' . trans('shop::app.customers.account.orders.status.options.closed') . '</p>';

                    case 'pending':
                        return '<p class="label-pending">' . trans('shop::app.customers.account.orders.status.options.pending') . '</p>';

                    case 'pending_payment':
                        return '<p class="label-pending">' . trans('shop::app.customers.account.orders.status.options.pending-payment') . '</p>';

                    case 'fraud':
                        return '<p class="label-canceled">' . trans('shop::app.customers.account.orders.status.options.fraud') . '</p>';
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
            'title'  => trans('shop::app.customers.account.orders.action-view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('shop.customers.account.orders.view', $row->id);
            },
        ]);
    }
}
