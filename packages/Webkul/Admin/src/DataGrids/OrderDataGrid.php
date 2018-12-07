<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * OrderDataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderDataGrid
{

    /**
     * The Data Grid implementation for orders
     */
    public function createOrderDataGrid()
    {
        return DataGrid::make([
            'name' => 'orders',
            'table' => 'orders as or',
            'select' => 'or.id',
            'perpage' => 10,
            'aliased' => false, //True in case of joins else aliasing key required on all cases

            'massoperations' =>[
                // [
                //     'route' => route('admin.datagrid.delete'),
                //     'method' => 'DELETE',
                //     'label' => 'Delete',
                //     'type' => 'button',
                // ],
            ],

            'actions' => [
                [
                    'type' => 'View',
                    'route' => 'admin.sales.orders.view',
                    // 'confirm_text' => 'Do you really want to view this record?',
                    'icon' => 'icon eye-icon',
                    'icon-alt' => 'View'
                ]
            ],

            'join' => [],

            //use aliasing on secodary columns if join is performed
            'columns' => [
                [
                    'name' => 'or.id',
                    'alias' => 'orderid',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ], [
                    'name' => 'CONCAT(or.customer_first_name, " ", or.customer_last_name)',
                    'alias' => 'oafirstname',
                    'type' => 'string',
                    'label' => 'Billed To',
                    'sortable' => false,
                ], [
                    'name' => 'or.base_grand_total',
                    'alias' => 'orbasegrandtotal',
                    'type' => 'string',
                    'label' => 'Base Total',
                    'sortable' => true,
                    'wrapper' => function ($value) {
                        return core()->formatBasePrice($value);
                    }
                ], [
                    'name' => 'or.grand_total',
                    'alias' => 'oagrandtotal',
                    'type' => 'string',
                    'label' => 'Grand Total',
                    'sortable' => false,
                    'wrapper' => function ($value) {
                        return core()->formatBasePrice($value);
                    }
                ], [
                    'name' => 'or.created_at',
                    'alias' => 'createdat',
                    'type' => 'datetime',
                    'label' => 'Order Date',
                    'sortable' => true,
                ], [
                    'name' => 'or.channel_name',
                    'alias' => 'channelname',
                    'type' => 'string',
                    'label' => 'Channel Name',
                    'sortable' => true,
                ], [
                    'name' => 'or.status',
                    'alias' => 'orstatus',
                    'type' => 'string',
                    'label' => 'Status',
                    'sortable' => true,
                    'closure' => true, //to be used when ever wrappers or callables are used
                    'wrapper' => function ($value) {
                        if($value == 'processing')
                            return '<span class="badge badge-md badge-success">Processing</span>';
                        else if($value == 'completed')
                            return '<span class="badge badge-md badge-success">Completed</span>';
                        else if($value == "canceled")
                            return '<span class="badge badge-md badge-danger">Canceled</span>';
                        else if($value == "closed")
                            return '<span class="badge badge-md badge-info">Closed</span>';
                        else if($value == "pending")
                            return '<span class="badge badge-md badge-warning">Pending</span>';
                        else if($value == "pending_payment")
                            return '<span class="badge badge-md badge-warning">Pending Payment</span>';
                        else if($value == "fraud")
                            return '<span class="badge badge-md badge-danger">Fraud</span>';
                    },
                ],
            ],

            'filterable' => [
                [
                    'column' => 'or.id',
                    'alias' => 'orderid',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'or.status',
                    'alias' => 'orstatus',
                    'type' => 'string',
                    'label' => 'Status'
                ], [
                    'column' => 'or.created_at',
                    'alias' => 'createdat',
                    'type' => 'string',
                    'label' => 'Order Date',
                ],
            ],

            //don't use aliasing in case of searchables
            'searchable' => [
                [
                    'column' => 'or.id',
                    'alias' => 'orderid',
                    'type' => 'number',
                ], [
                    'column' => 'or.status',
                    'alias' => 'orstatus',
                    'type' => 'string',
                ]
            ],

            //list of viable operators that will be used
            'operators' => [
                'eq' => "=",
                'lt' => "<",
                'gt' => ">",
                'lte' => "<=",
                'gte' => ">=",
                'neqs' => "<>",
                'neqn' => "!=",
                'like' => "like",
                'nlike' => "not like",
            ],

            // 'css' => []
        ]);
    }

    public function render($pagination = true)
    {
        return $this->createOrderDataGrid()->render($pagination);
    }
}