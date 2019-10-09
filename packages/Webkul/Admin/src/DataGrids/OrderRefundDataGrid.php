<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * OrderRefundDataGrid Class
 *
 * @author Prashant Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderRefundDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('refunds')
                ->select('refunds.id', 'orders.increment_id', 'refunds.state', 'refunds.base_grand_total', 'refunds.created_at')
                ->leftJoin('orders', 'refunds.order_id', '=', 'orders.id')
                ->leftJoin('order_address as order_address_billing', function($leftJoin) {
                    $leftJoin->on('order_address_billing.order_id', '=', 'orders.id')
                        ->where('order_address_billing.address_type', 'billing');
                })
                ->addSelect(DB::raw('CONCAT(order_address_billing.first_name, " ", order_address_billing.last_name) as billed_to'));

        $this->addFilter('billed_to', DB::raw('CONCAT(order_address_billing.first_name, " ", order_address_billing.last_name)'));
        $this->addFilter('id', 'refunds.id');
        $this->addFilter('increment_id', 'orders.increment_id');
        $this->addFilter('state', 'refunds.state');
        $this->addFilter('base_grand_total', 'refunds.base_grand_total');
        $this->addFilter('created_at', 'refunds.created_at');

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
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'increment_id',
            'label' => trans('admin::app.datagrid.order-id'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('admin::app.datagrid.refunded'),
            'type' => 'price',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'billed_to',
            'label' => trans('admin::app.datagrid.billed-to'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('admin::app.datagrid.refund-date'),
            'type' => 'datetime',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'title' => 'Order Refund View',
            'method' => 'GET', // use GET request only for redirect purposes
            'route' => 'admin.sales.refunds.view',
            'icon' => 'icon eye-icon'
        ]);
    }
}