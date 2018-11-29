<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * OrderInvoicesDataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class OrderInvoicesDataGrid
{
    /**
     * The Order invoices Data Grid implementation.
     *
     * @var OrderInvoicesDataGrid
     * for invoices of orders
     */
    public function createOrderInvoicesDataGrid()
    {

        return DataGrid::make([
            'name' => 'invoices',
            'table' => 'invoices as inv',
            'select' => 'inv.id',
            'perpage' => 10,
            'aliased' => false,

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
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to view this record?',
                    'icon' => 'icon pencil-lg-icon',
                ],
                // [
                //     'type' => 'Delete',
                //     'route' => route('admin.datagrid.delete'),
                //     'confirm_text' => 'Do you really want to do this?',
                //     'icon' => 'icon trash-icon',
                // ]
            ],

            'join' => [
                // [
                //     'join' => 'leftjoin',
                //     'table' => 'orders as ors',
                //     'primaryKey' => 'inv.order_id',
                //     'condition' => '=',
                //     'secondaryKey' => 'ors.id',
                // ]
            ],

            //use aliasing on secodary columns if join is performed

            'columns' => [
                [
                    'name' => 'inv.id',
                    'alias' => 'invid',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true
                ], [
                    'name' => 'inv.order_id',
                    'alias' => 'invorderid',
                    'type' => 'number',
                    'label' => 'Order ID',
                    'sortable' => true
                ],
                // [
                //     'name' => 'inv.state',
                //     'alias' => 'invstate',
                //     'type' => 'string',
                //     'label' => 'State',
                //     'sortable' => false
                // ],
                [
                    'name' => 'inv.grand_total',
                    'alias' => 'invgrandtotal',
                    'type' => 'number',
                    'label' => 'Amount',
                    'sortable' => true
                ], [
                    'name' => 'inv.created_at',
                    'alias' => 'invcreated_at',
                    'type' => 'date',
                    'label' => 'Invoice Date',
                    'sortable' => true
                ]
            ],

            'filterable' => [
                [
                    'column' => 'inv.id',
                    'alias' => 'invid',
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
        return $this->createOrderInvoicesDataGrid()->render();
    }
}