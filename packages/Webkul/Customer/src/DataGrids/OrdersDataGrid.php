<?php

namespace Webkul\Customer\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;

class OrdersDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $getDbPrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('orders')
            ->leftJoin('addresses as order_address_billing', function ($leftJoin) {
                $leftJoin->on('order_address_billing.order_id', '=', 'orders.id')
                    ->where('order_address_billing.address_type', OrderAddress::ADDRESS_TYPE_BILLING);
            })
            ->leftJoin('order_payment', 'orders.id', '=', 'order_payment.order_id')
            ->select(
                'orders.id',
                'orders.increment_id',
                'order_payment.method',
                'orders.base_grand_total',
                'orders.created_at',
                'channel_name',
                'status',
                'order_address_billing.email as customer_email',
                'orders.cart_id as image',
                DB::raw('CONCAT('.$getDbPrefix.'order_address_billing.first_name, " ", '.$getDbPrefix.'order_address_billing.last_name) as full_name'),
                DB::raw('CONCAT('.$getDbPrefix.'order_address_billing.address, ", ", '.$getDbPrefix.'order_address_billing.city,", ", '.$getDbPrefix.'order_address_billing.state, ", ", '.$getDbPrefix.'order_address_billing.country) as location')
            )
            ->where('orders.customer_id', request()->route('id'));

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
            'label'      => trans('admin::app.sales.orders.index.datagrid.order-id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.sales.orders.index.datagrid.status'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => [
                        [
                            'label' => trans('admin::app.sales.orders.index.datagrid.processing'),
                            'value' => Order::STATUS_PROCESSING,
                        ],
                        [
                            'label' => trans('admin::app.sales.orders.index.datagrid.completed'),
                            'value' => Order::STATUS_COMPLETED,
                        ],
                        [
                            'label' => trans('admin::app.sales.orders.index.datagrid.canceled'),
                            'value' => Order::STATUS_CANCELED,
                        ],
                        [
                            'label' => trans('admin::app.sales.orders.index.datagrid.closed'),
                            'value' => Order::STATUS_CLOSED,
                        ],
                        [
                            'label' => trans('admin::app.sales.orders.index.datagrid.pending'),
                            'value' => Order::STATUS_PENDING,
                        ],
                        [
                            'label' => trans('admin::app.sales.orders.index.datagrid.pending-payment'),
                            'value' => Order::STATUS_PENDING_PAYMENT,
                        ],
                        [
                            'label' => trans('admin::app.sales.orders.index.datagrid.fraud'),
                            'value' => Order::STATUS_FRAUD,
                        ],
                    ],
                ],
            ],
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case Order::STATUS_PROCESSING:
                        return '<p class="label-processing">'.trans('admin::app.sales.orders.index.datagrid.processing').'</p>';

                    case Order::STATUS_COMPLETED:
                        return '<p class="label-active">'.trans('admin::app.sales.orders.index.datagrid.completed').'</p>';

                    case Order::STATUS_CANCELED:
                        return '<p class="label-canceled">'.trans('admin::app.sales.orders.index.datagrid.canceled').'</p>';

                    case Order::STATUS_CLOSED:
                        return '<p class="label-closed">'.trans('admin::app.sales.orders.index.datagrid.closed').'</p>';

                    case Order::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('admin::app.sales.orders.index.datagrid.pending').'</p>';

                    case Order::STATUS_PENDING_PAYMENT:
                        return '<p class="label-pending">'.trans('admin::app.sales.orders.index.datagrid.pending-payment').'</p>';

                    case Order::STATUS_FRAUD:
                        return '<p class="label-canceled">'.trans('admin::app.sales.orders.index.datagrid.fraud').'</p>';
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'base_grand_total',
            'label'      => trans('admin::app.sales.orders.index.datagrid.grand-total'),
            'type'       => 'price',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'method',
            'label'      => trans('admin::app.sales.orders.index.datagrid.pay-via'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
            'closure'    => function ($row) {
                return core()->getConfigData('sales.payment_methods.'.$row->method.'.title');
            },
        ]);

        $this->addColumn([
            'index'      => 'channel_name',
            'label'      => trans('admin::app.sales.orders.index.datagrid.channel-name'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'full_name',
            'label'      => trans('admin::app.sales.orders.index.datagrid.customer'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        /**
         * Searchable dropdown sample. In testing phase.
         */
        $this->addColumn([
            'index'      => 'customer_email',
            'label'      => trans('admin::app.sales.orders.index.datagrid.email'),
            'type'       => 'dropdown',
            'options'    => [
                'type'   => 'searchable',
                'params' => [
                    'repository' => \Webkul\Customer\Repositories\CustomerRepository::class,
                    'column'     => [
                        'label' => 'email',
                        'value' => 'email',
                    ],
                ],
            ],
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'location',
            'label'      => trans('admin::app.sales.orders.index.datagrid.location'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.sales.orders.index.datagrid.date'),
            'type'       => 'date_range',
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
        if (bouncer()->hasPermission('sales.orders.view')) {
            $this->addAction([
                'icon'   => 'icon-view',
                'title'  => trans('admin::app.sales.orders.index.datagrid.view'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.orders.view', $row->id);
                },
            ]);
        }
    }
}
