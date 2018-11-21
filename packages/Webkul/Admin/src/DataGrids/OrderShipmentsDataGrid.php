<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

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
            'aliased' => false,

            'massoperations' =>[
                [
                    'route' => route('admin.datagrid.delete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button',
                ],
            ],

            'actions' => [
                // [
                //     'type' => 'View',
                //     'route' => route('admin.datagrid.delete'),
                //     'confirm_text' => 'Do you really want to do this?',
                //     'icon' => 'icon pencil-lg-icon',
                // ], [
                //     'type' => 'Delete',
                //     'route' => route('admin.datagrid.delete'),
                //     'confirm_text' => 'Do you really want to do this?',
                //     'icon' => 'icon trash-icon',
                // ]
            ],

            'join' => [],

            //use aliasing on secodary columns if join is performed

            'columns' => [
                [
                    'name' => 'ship.id',
                    'alias' => 'shipID',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true
                ], [
                    'name' => 'ship.status',
                    'alias' => 'shipstatus',
                    'type' => 'string',
                    'label' => 'Status',
                    'sortable' => true,
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
                    },
                ],
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
                //     'column' => 'or.id',
                //     'alias' => 'orderid',
                //     'type' => 'number',
                //     'label' => 'ID',
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