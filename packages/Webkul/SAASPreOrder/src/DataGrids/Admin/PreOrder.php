<?php

namespace Webkul\SAASPreOrder\DataGrids\Admin;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * PreOrder Class
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class PreOrder extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('pre_order_items')
                ->leftJoin('order_items', 'pre_order_items.order_item_id', '=', 'order_items.id')
                ->leftJoin('orders', 'pre_order_items.order_id', '=', 'orders.id')
                ->leftJoin('order_items as payment_order_items', 'pre_order_items.payment_order_item_id', '=', 'payment_order_items.id')
                ->addSelect('pre_order_items.id', 'pre_order_items.order_id', 'order_items.name as product_name', 'orders.customer_email', 'order_item_id', 'preorder_type', 'pre_order_items.status', 'base_paid_amount', 'pre_order_items.email_sent', 'base_remaining_amount', 'payment_order_items.order_id as payment_order_id')
                ->addSelect(DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name) as customer_name'));

        $this->addFilter('id', 'pre_order_items.id');
        $this->addFilter('status', 'pre_order_items.status');
        $this->addFilter('order_id', 'pre_order_items.order_id');
        $this->addFilter('payment_order_id', 'payment_order_items.order_id');
        $this->addFilter('product_name', 'order_items.name');
        $this->addFilter('customer_name', DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name)'));

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('preorder::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'order_id',
            'label' => trans('preorder::app.datagrid.order-id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function ($row) {
                return '<a href="' . route('admin.sales.orders.view', $row->order_id) . '">' . $row->order_id . '</a>';
            }
        ]);

        $this->addColumn([
            'index' => 'payment_order_id',
            'label' => trans('preorder::app.datagrid.payment-order-id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => false,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function ($row) {
                if ($row->payment_order_id)
                    return '<a href="' . route('admin.sales.orders.view', $row->payment_order_id) . '">' . $row->payment_order_id . '</a>';
                else
                    return 'N/A';
            }
        ]);

        $this->addColumn([
            'index' => 'product_name',
            'label' => trans('preorder::app.datagrid.product-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'customer_name',
            'label' => trans('preorder::app.datagrid.customer-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'customer_email',
            'label' => trans('preorder::app.datagrid.customer-email'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_paid_amount',
            'label' => trans('preorder::app.datagrid.paid-amount'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_remaining_amount',
            'label' => trans('preorder::app.datagrid.remaining-amount'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'preorder_type',
            'label' => trans('preorder::app.datagrid.preorder-type'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'wrapper' => function ($value) {
                if ($value->preorder_type == 'partial')
                    return trans('preorder::app.datagrid.partial-payment');
                else
                    return trans('preorder::app.datagrid.complete-payment');
            }
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('preorder::app.datagrid.status'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'wrapper' => function ($value) {
                if ($value->status == 'pending')
                    return trans('preorder::app.datagrid.pending');
                if ($value->status == 'processing')
                    return trans('preorder::app.datagrid.processing');
                else
                    return trans('preorder::app.datagrid.completed');
            }
        ]);

        $this->addColumn([
            'index' => 'email_sent',
            'label' => trans('preorder::app.datagrid.email-sent'),
            'type' => 'boolean',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function($value) {
                if ($value->email_sent == 1)
                    return trans('preorder::app.datagrid.yes');
                else
                    return trans('preorder::app.datagrid.no');
            }
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => 'Notify Customer',
            'action' => route('admin.preorder.preorders.notify-customer'),
            'method' => 'POST'
        ]);
    }
}