<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * OrderDataGrid Class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('orders')
                ->leftJoin('order_address as order_address_shipping', function($leftJoin) {
                    $leftJoin->on('order_address_shipping.order_id', '=', 'orders.id')
                        ->where('order_address_shipping.address_type', 'shipping');
                })
                ->leftJoin('order_address as order_address_billing', function($leftJoin) {
                    $leftJoin->on('order_address_billing.order_id', '=', 'orders.id')
                        ->where('order_address_billing.address_type', 'billing');
                })
                ->addSelect('orders.id', 'base_sub_total', 'base_grand_total', 'orders.created_at', 'channel_name', 'status')
                ->addSelect(DB::raw('CONCAT(order_address_billing.first_name, " ", order_address_billing.last_name) as billed_to'))
                ->addSelect(DB::raw('CONCAT(order_address_shipping.first_name, " ", order_address_shipping.last_name) as shipped_to'));

        $this->addFilter('billed_to', DB::raw('CONCAT(order_address_billing.first_name, " ", order_address_billing.last_name)'));
        $this->addFilter('shipped_to', DB::raw('CONCAT(order_address_shipping.first_name, " ", order_address_shipping.last_name)'));
        $this->addFilter('id', 'orders.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'base_sub_total',
            'label' => trans('admin::app.datagrid.sub-total'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('admin::app.datagrid.grand-total'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('admin::app.datagrid.order-date'),
            'type' => 'datetime',
            'sortable' => true,
            'searchable' => false,
        ]);

        $this->addColumn([
            'index' => 'channel_name',
            'label' => trans('admin::app.datagrid.channel-name'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
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
            }
        ]);

        $this->addColumn([
            'index' => 'billed_to',
            'label' => trans('admin::app.datagrid.billed-to'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'shipped_to',
            'label' => trans('admin::app.datagrid.shipped-to'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'View',
            'route' => 'admin.sales.orders.view',
            'icon' => 'icon eye-icon'
        ]);
    }
}