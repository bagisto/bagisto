<?php

namespace Webkul\Shop\DataGrids\RMA;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\Order;

class OrderDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $globalReturnDays = core()->getConfigData('sales.rma.setting.default_allow_days') ?? 0;

        $allowedProductTypes = core()->getConfigData('sales.rma.setting.select_allowed_product_type');

        $allowedOrderStatus = core()->getConfigData('sales.rma.setting.select_allowed_order_status');

        $customerId = auth()->guard('customer')->user()->id;
        
        $tablePrefix = DB::getTablePrefix();

        $rmaItemsSubquery = DB::table('rma_items')
            ->select('order_item_id', DB::raw('SUM(quantity) as total_rma_qty'))
            ->groupBy('order_item_id');

        $queryBuilder = DB::table('orders')
            ->select([
                'orders.id',
                'orders.increment_id',
                'orders.status',
                'orders.created_at',
                'orders.grand_total',
                'orders.order_currency_code',
                'order_payment.method_title',
                DB::raw("SUM({$tablePrefix}order_items.qty_ordered) as total_qty_ordered"),
                DB::raw("COALESCE(SUM(rma_items_agg.total_rma_qty), 0) as total_rma_qty")
            ])
            ->leftJoin('order_payment', 'orders.id', '=', 'order_payment.order_id')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoinSub($rmaItemsSubquery, 'rma_items_agg', 'order_items.id', '=', 'rma_items_agg.order_item_id');

        $queryBuilder->leftJoin('product_attribute_values as pav_allow_rma', function ($join) {
                $join->on('products.id', '=', 'pav_allow_rma.product_id')
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('attributes')
                            ->whereColumn('attributes.id', 'pav_allow_rma.attribute_id')
                            ->where('attributes.code', 'allow_rma');
                    });
            })
            ->leftJoin('product_attribute_values as pav_rma_rules', function ($join) {
                $join->on('products.id', '=', 'pav_rma_rules.product_id')
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('attributes')
                            ->whereColumn('attributes.id', 'pav_rma_rules.attribute_id')
                            ->where('attributes.code', 'rma_rule_id');
                    });
            })
            ->leftJoin('rma_rules', 'pav_rma_rules.integer_value', '=', 'rma_rules.id');

        $queryBuilder->where('orders.customer_id', $customerId)
            ->whereNotIn('orders.status', [
                Order::STATUS_CANCELED,
                Order::STATUS_CLOSED,
                Order::STATUS_FRAUD,
                Order::STATUS_PENDING_PAYMENT,
            ]);

        /**
         * If the allowed order status is set to 'completed', we filter the orders to include only those with the 'completed' status.
         */
        if ($allowedOrderStatus == Order::STATUS_COMPLETED) {
            $queryBuilder->where('orders.status', Order::STATUS_COMPLETED);
        }

        /**
         * If specific product types are configured for RMA eligibility, we filter the orders to include only those containing products of the specified types.
         */
        if (! empty($allowedProductTypes)) {
            $productTypesArray = array_filter(
                array_map('trim', explode(',', $allowedProductTypes))
            );
            
            if (! empty($productTypesArray)) {
                $queryBuilder->whereIn('products.type', $productTypesArray);
            }
        }

        /**
         * Check for RMA eligibility based on product types and RMA rules.
         */
        $queryBuilder->where(function ($query) use ($globalReturnDays, $tablePrefix) {
            /**
             * Products that have RMA explicitly allowed and are governed by active RMA rules,
             */
            $query->where(function ($q) use ($tablePrefix) {
                $q->where('pav_allow_rma.boolean_value', 1)
                    ->where('rma_rules.status', 1)
                    ->whereRaw("DATEDIFF(NOW(), {$tablePrefix}orders.created_at) <= {$tablePrefix}rma_rules.return_period");
            });
            
            /**
             * Products that do not have RMA explicitly allowed or are governed by inactive RMA rules (fallback to global setting).,
             */
            if ($globalReturnDays > 0) {
                $query->orWhere(function ($q) use ($globalReturnDays, $tablePrefix) {
                    $q->where(function ($sub) {
                        $sub->whereNull('pav_allow_rma.boolean_value')
                            ->orWhere('pav_allow_rma.boolean_value', 0)
                            ->orWhere('rma_rules.status', 0);
                    })
                    ->whereRaw("DATEDIFF(NOW(), {$tablePrefix}orders.created_at) <= ?", [$globalReturnDays]);
                });
            }
        });

        $queryBuilder->groupBy('orders.id')->havingRaw("SUM({$tablePrefix}order_items.qty_ordered) > COALESCE(SUM(rma_items_agg.total_rma_qty), 0)");

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
            'index'              => 'status',
            'label'              => trans('shop::app.customers.account.orders.status.title'),
            'type'               => 'string',
            'filterable_type'    => 'dropdown',
            'searchable'         => true,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_options' => [
                [
                    'label' => trans('shop::app.customers.account.orders.status.processing'),
                    'value' => Order::STATUS_PROCESSING,
                ], [
                    'label' => trans('shop::app.customers.account.orders.status.completed'),
                    'value' => Order::STATUS_COMPLETED,
                ], [
                    'label' => trans('shop::app.customers.account.orders.status.canceled'),
                    'value' => Order::STATUS_CANCELED,
                ], [
                    'label' => trans('shop::app.customers.account.orders.status.closed'),
                    'value' => Order::STATUS_CLOSED,
                ], [
                    'label' => trans('shop::app.customers.account.orders.status.pending'),
                    'value' => Order::STATUS_PENDING,
                ], [
                    'label' => trans('shop::app.customers.account.orders.status.pending-payment'),
                    'value' => Order::STATUS_PENDING_PAYMENT,
                ], [
                    'label' => trans('shop::app.customers.account.orders.status.fraud'),
                    'value' => Order::STATUS_FRAUD,
                ],
            ],
            'closure'            => function ($row) {
                switch ($row->status) {
                    case Order::STATUS_PROCESSING:
                        return '<p class="label-processing">'.trans('shop::app.customers.account.orders.status.processing').'</p>';

                    case Order::STATUS_COMPLETED:
                        return '<p class="label-active">'.trans('shop::app.customers.account.orders.status.completed').'</p>';

                    case Order::STATUS_CANCELED:
                        return '<p class="label-canceled">'.trans('shop::app.customers.account.orders.status.canceled').'</p>';

                    case Order::STATUS_CLOSED:
                        return '<p class="label-closed">'.trans('shop::app.customers.account.orders.status.closed').'</p>';

                    case Order::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('shop::app.customers.account.orders.status.pending').'</p>';

                    case Order::STATUS_PENDING_PAYMENT:
                        return '<p class="label-pending">'.trans('shop::app.customers.account.orders.status.pending-payment').'</p>';

                    case Order::STATUS_FRAUD:
                        return '<p class="label-canceled">'.trans('shop::app.customers.account.orders.status.fraud').'</p>';
                }
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
            'label'              => trans('shop::app.customers.account.orders.pay-via'),
            'type'               => 'string',
            'filterable_type'    => 'dropdown',
            'searchable'         => true,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_options' => collect(config('payment_methods'))
                ->map(fn ($type) => ['label' => trans($type['title']), 'value' => $type['title']])
                ->values()
                ->toArray(),
            'closure'            => function ($row) {
                return '<span class="text-sm">'.trans('shop::app.customers.account.orders.pay-by', ['method' => '']).$row->method_title.'</span>';
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
