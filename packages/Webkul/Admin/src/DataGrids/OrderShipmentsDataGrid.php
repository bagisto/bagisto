<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;
use DB;

/**
 * OrderShipmentsDataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class OrderShipmentsDataGrid
{
    /**
     * The Order Shipments Data Grid implementation.
     *
     * @var OrderShipmentsDataGrid
     * for shipments of orders
     */
    public function createOrderShipmentsDataGrid()
    {
        return DataGrid::make([
            'name' => 'shipments',
            'table' => 'shipments as ship',
            'select' => 'ship.id',
            'perpage' => 10,
            'aliased' => true,

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
                    'route' => 'admin.sales.shipments.view',
                    // 'confirm_text' => 'Do you really want to view this record?',
                    'icon' => 'icon eye-icon',
                    'icon-alt' => 'View'
                ],
            ],

            'join' => [
                [
                    'join' => 'leftjoin',
                    'table' => 'orders as ors',
                    'primaryKey' => 'ship.order_id',
                    'condition' => '=',
                    'secondaryKey' => 'ors.id',
                ]
            ],

            //use aliasing on secodary columns if join is performed
            'columns' => [
                [
                    'name' => 'ship.id',
                    'alias' => 'shipID',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true
                ], [
                    'name' => 'ship.order_id',
                    'alias' => 'orderid',
                    'type' => 'number',
                    'label' => 'Order ID',
                    'sortable' => true
                ], [
                    'name' => 'ship.total_qty',
                    'alias' => 'shiptotalqty',
                    'type' => 'number',
                    'label' => 'Total Quantity',
                    'sortable' => true
                ], [
                    'name' => 'CONCAT(ors.customer_first_name, " ", ors.customer_last_name)',
                    'alias' => 'ordercustomerfirstname',
                    'type' => 'string',
                    'label' => 'Customer Name',
                    'sortable' => false,
                ], [
                    'name' => 'ors.created_at',
                    'alias' => 'orscreated',
                    'type' => 'date',
                    'label' => 'Order Date',
                    'sortable' => true
                ], [
                    'name' => 'ship.created_at',
                    'alias' => 'shipdate',
                    'type' => 'string',
                    'label' => 'Shipment Date',
                    'sortable' => false
                ]
            ],

            'filterable' => [
                [
                    'column' => 'ship.id',
                    'alias' => 'shipID',
                    'type' => 'number',
                    'label' => 'ID',
                ]
            ],
            //don't use aliasing in case of searchables

            'searchable' => [
                // [
                //     'column' => 'ors.customer_first_name',
                //     'alias' => 'ordercustomerfirstname',
                //     'type' => 'string',
                // ]
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

    public function render()
    {
        return $this->createOrderShipmentsDataGrid()->render();
    }
}