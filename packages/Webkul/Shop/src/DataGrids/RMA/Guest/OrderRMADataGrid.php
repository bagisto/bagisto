<?php

namespace Webkul\Shop\DataGrids\RMA\Guest;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderPayment;

class OrderRMADataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $globalReturnDays = core()->getConfigData('sales.rma.setting.default_allow_days');

        $tablePrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('orders')
            ->addSelect(
                'orders.id as id',
                'orders.increment_id as increment_id',
                'orders.status as status',
                'orders.created_at as created_at',
                'orders.grand_total as grand_total',
                'orders.order_currency_code as order_currency_code',
                'order_payment.method_title as method_title',
                DB::raw('SUM('.$tablePrefix.'order_items.qty_ordered) as total_qty_ordered'),
                DB::raw('IFNULL(SUM('.$tablePrefix.'rma_items_aggregated.total_rma_qty), 0) as total_rma_qty')
            )
            ->leftJoin('order_payment', 'orders.id', '=', 'order_payment.order_id')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('rma', 'orders.id', '=', 'rma.order_id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('attributes as attr_allow_rma', function ($join) {
                $join->on(DB::raw('1'), '=', DB::raw('1'))
                    ->where('attr_allow_rma.code', '=', 'allow_rma');
            })
            ->leftJoin('product_attribute_values as pav_allow_rma', function ($join) {
                $join->on('products.id', '=', 'pav_allow_rma.product_id')
                    ->on('pav_allow_rma.attribute_id', '=', 'attr_allow_rma.id');
            })
            ->leftJoin('attributes as attr_rma_rules', function ($join) {
                $join->on(DB::raw('1'), '=', DB::raw('1'))
                    ->where('attr_rma_rules.code', '=', 'rma_rule_id');
            })
            ->leftJoin('product_attribute_values as pav_rma_rules', function ($join) {
                $join->on('products.id', '=', 'pav_rma_rules.product_id')
                    ->on('pav_rma_rules.attribute_id', '=', 'attr_rma_rules.id');
            })
            ->leftJoin('rma_rules', 'pav_rma_rules.integer_value', '=', 'rma_rules.id')
            ->leftJoin(
                DB::raw('
                    (
                        SELECT order_item_id, SUM(quantity) as total_rma_qty
                        FROM '.$tablePrefix.'rma_items
                        GROUP BY order_item_id
                    ) as '.$tablePrefix.'rma_items_aggregated
                '),
                'order_items.id',
                '=',
                'rma_items_aggregated.order_item_id'
            )
            ->where('orders.id', session()->get('guestOrderId'))
            ->whereNotIn('orders.status', [
                Order::STATUS_CANCELED,
                Order::STATUS_CLOSED,
                Order::STATUS_FRAUD,
                Order::STATUS_PENDING_PAYMENT,
            ])
            ->whereIn('products.type', explode(',', core()->getConfigData('sales.rma.setting.select_allowed_product_type')))
            ->where(function ($query) use ($globalReturnDays, $tablePrefix) {
                $query->where(function ($q) use ($tablePrefix) {
                    $q->where('pav_allow_rma.boolean_value', 1)
                        ->where('rma_rules.status', 1)
                        ->whereRaw('DATEDIFF(NOW(), '.$tablePrefix.'orders.created_at) <= '.$tablePrefix.'rma_rules.return_period');
                })->orWhere(function ($q) use ($globalReturnDays, $tablePrefix) {
                    $q->where(function ($sub) {
                        $sub->whereNull('pav_allow_rma.boolean_value')
                            ->orWhere('pav_allow_rma.boolean_value', 0)
                            ->orWhere('rma_rules.status', 0);
                    })->whereRaw('DATEDIFF(NOW(), '.$tablePrefix.'orders.created_at) <= ?', [$globalReturnDays]);
                });
            })
            ->groupBy('orders.id')
            ->havingRaw('SUM('.$tablePrefix.'order_items.qty_ordered) > IFNULL(SUM('.$tablePrefix.'rma_items_aggregated.total_rma_qty), 0)');

        if (core()->getConfigData('sales.rma.setting.select_allowed_order_status') == Order::STATUS_COMPLETED) {
            $queryBuilder->whereIn('orders.status', [
                Order::STATUS_COMPLETED,
            ]);
        }

        $this->addFilter('id', 'orders.id');
        $this->addFilter('status', 'orders.status');
        $this->addFilter('grand_total', 'orders.grand_total');
        $this->addFilter('method_title', 'order_payment.method_title');
        $this->addFilter('created_at', 'orders.created_at');

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'increment_id',
            'label'      => trans('shop::app.customers.account.orders.order-id'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                return '<span class="text-sm text-blue-500"><a href="'.route('shop.customers.account.orders.view', ['id' => $row->increment_id]).'">'.'#'.$row->increment_id.'</a></span>';
            },
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('shop::app.customers.account.orders.order-date'),
            'type'            => 'date',
            'searchable'      => true,
            'sortable'        => true,
            'filterable'      => true,
            'filterable_type' => 'date_range',
            'closure'         => function ($row) {
                return '<span class="text-sm">'.$row->created_at.'</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'grand_total',
            'label'      => trans('shop::app.customers.account.orders.total'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                return '<span class="text-sm">'.core()->formatPrice($row->grand_total, $row->order_currency_code).'</span>';
            },
        ]);

        $this->addColumn([
            'index'              => 'method_title',
            'label'              => trans('admin::app.sales.orders.index.datagrid.pay-via'),
            'type'               => 'string',
            'searchable'         => true,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => OrderPayment::distinct()
                ->get(['method_title'])
                ->map(function ($item) {
                    return [
                        'label' => $item->method_title,
                        'value' => $item->method_title,
                    ];
                })
                ->toArray(),
            'closure'            => function ($row) {
                return '<span class="text-sm">'.trans('admin::app.sales.orders.index.datagrid.pay-by', ['method' => '']).$row->method_title.'</span>';
            },
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('shop::app.customers.account.orders.status.title'),
            'type'               => 'string',
            'searchable'         => true,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.sales.orders.index.datagrid.processing'),
                    'value' => Order::STATUS_PROCESSING,
                ], [
                    'label' => trans('admin::app.sales.orders.index.datagrid.completed'),
                    'value' => Order::STATUS_COMPLETED,
                ], [
                    'label' => trans('admin::app.sales.orders.index.datagrid.canceled'),
                    'value' => Order::STATUS_CANCELED,
                ], [
                    'label' => trans('admin::app.sales.orders.index.datagrid.closed'),
                    'value' => Order::STATUS_CLOSED,
                ], [
                    'label' => trans('admin::app.sales.orders.index.datagrid.pending'),
                    'value' => Order::STATUS_PENDING,
                ], [
                    'label' => trans('admin::app.sales.orders.index.datagrid.pending-payment'),
                    'value' => Order::STATUS_PENDING_PAYMENT,
                ], [
                    'label' => trans('admin::app.sales.orders.index.datagrid.fraud'),
                    'value' => Order::STATUS_FRAUD,
                ],
            ],
            'closure'            => function ($row) {
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
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
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
