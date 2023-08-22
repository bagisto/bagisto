<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Sales\Models\OrderAddress;
use Webkul\DataGrid\DataGrid;

class OrderDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('orders')
            ->leftJoin('addresses as order_address_shipping', function ($leftJoin) {
                $leftJoin->on('order_address_shipping.order_id', '=', 'orders.id')
                    ->where('order_address_shipping.address_type', OrderAddress::ADDRESS_TYPE_SHIPPING);
            })
            ->leftJoin('addresses as order_address_billing', function ($leftJoin) {
                $leftJoin->on('order_address_billing.order_id', '=', 'orders.id')
                    ->where('order_address_billing.address_type', OrderAddress::ADDRESS_TYPE_BILLING);
            })
            ->leftJoin('order_payment', 'orders.id', '=', 'order_payment.order_id')
            ->select(
                'orders.id',
                'order_payment.method',
                'orders.increment_id',
                'orders.base_grand_total',
                'orders.created_at',
                'channel_name',
                'status',
                'customer_email',
                DB::raw('CONCAT(' . DB::getTablePrefix() . 'orders.customer_first_name, " ", ' . DB::getTablePrefix() . 'orders.customer_last_name) as full_name'),
                DB::raw('CONCAT(' . DB::getTablePrefix() . 'order_address_billing.city, ", ", ' . DB::getTablePrefix() . 'order_address_billing.state,", ", ' . DB::getTablePrefix() . 'order_address_billing.country) as location'),
                DB::raw('CONCAT(' . DB::getTablePrefix() . 'order_address_shipping.first_name, " ", ' . DB::getTablePrefix() . 'order_address_shipping.last_name) as shipped_to')
            );

        // $this->addFilter('billed_to', DB::raw('CONCAT(' . DB::getTablePrefix() . 'order_address_billing.first_name, " ", ' . DB::getTablePrefix() . 'order_address_billing.last_name)'));
        // $this->addFilter('shipped_to', DB::raw('CONCAT(' . DB::getTablePrefix() . 'order_address_shipping.first_name, " ", ' . DB::getTablePrefix() . 'order_address_shipping.last_name)'));
        $this->addFilter('increment_id', 'orders.increment_id');
        $this->addFilter('created_at', 'orders.created_at');

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
            'label'      => trans('admin::app.sales.orders.index.order-id'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.sales.orders.index.date'),
            'type'       => 'datetime',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.sales.orders.index.status'),
            'type'       => 'checkbox',
            'options'    => [
                'processing'      => trans('shop::app.customer.account.order.index.processing'),
                'completed'       => trans('shop::app.customer.account.order.index.completed'),
                'canceled'        => trans('shop::app.customer.account.order.index.canceled'),
                'closed'          => trans('shop::app.customer.account.order.index.closed'),
                'pending'         => trans('shop::app.customer.account.order.index.pending'),
                'pending_payment' => trans('shop::app.customer.account.order.index.pending-payment'),
                'fraud'           => trans('shop::app.customer.account.order.index.fraud'),
            ],
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                switch ($value->status) {
                    case 'processing':
                        return '<span class="badge badge-md badge-success">' . trans('admin::app.sales.orders.order-status-processing') . '</span>';
                        break;

                    case 'completed':
                        return '<span class="badge badge-md badge-success">' . trans('admin::app.sales.orders.order-status-success') . '</span>';
                        break;

                    case 'canceled':
                        return '<span class="badge badge-md badge-danger">' . trans('admin::app.sales.orders.order-status-canceled') . '</span>';
                        break;

                    case 'closed':
                        return '<span class="badge badge-md badge-info">' . trans('admin::app.sales.orders.order-status-closed') . '</span>';
                        break;

                    case 'pending':
                        return '<span class="badge badge-md badge-warning">' . trans('admin::app.sales.orders.order-status-pending') . '</span>';
                        break;

                    case 'pending_payment':
                        return '<span class="badge badge-md badge-warning">' . trans('admin::app.sales.orders.order-status-pending-payment') . '</span>';
                        break;

                    case 'fraud':
                        return '<span class="badge badge-md badge-danger">' . trans('admin::app.sales.orders.order-status-fraud') . '</span>';
                        break;
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'base_grand_total',
            'label'      => trans('admin::app.sales.orders.index.grand-total'),
            'type'       => 'price',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'method',
            'label'      => trans('admin::app.sales.orders.index.pay-via'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'channel_name',
            'label'      => trans('admin::app.sales.orders.index.channel-name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'full_name',
            'label'      => trans('admin::app.sales.orders.index.customer'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'customer_email',
            'label'      => trans('admin::app.sales.orders.index.email'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'location',
            'label'      => trans('admin::app.sales.orders.index.location'),
            'type'       => 'string',
            'searchable' => false,
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
            'icon'   => 'icon-view',
            'title'  => trans('admin::app.sales.orders.index.view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.sales.orders.view', $row->id);
            },
        ]);
    }
}
