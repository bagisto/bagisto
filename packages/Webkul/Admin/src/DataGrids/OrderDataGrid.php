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
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('orders')->select('id', 'base_grand_total', 'grand_total', 'created_at', 'channel_name', 'status')->addSelect(DB::raw('CONCAT(customer_first_name, " ", customer_last_name) as full_name'));

        $this->setQueryBuilder($queryBuilder);
    }

    public function setIndex() {
        $this->index = 'id'; //the column that needs to be treated as index column
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('admin::app.datagrid.base-total'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
            'wrapper' => function ($value) {
                return core()->formatBasePrice($value);
            }
        ]);

        $this->addColumn([
            'index' => 'grand_total',
            'label' => trans('admin::app.datagrid.grand-total'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
            'wrapper' => function ($value) {
                return core()->formatBasePrice($value);
            }
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('admin::app.datagrid.order-date'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'width' => '100px',
        ]);

        $this->addColumn([
            'index' => 'channel_name',
            'label' => trans('admin::app.datagrid.channel-name'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px',
            'closure' => true,
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
            }
        ]);

        $this->addColumn([
            'index' => 'full_name',
            'label' => trans('admin::app.datagrid.billed-to'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'width' => '100px'
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'View',
            'route' => 'admin.sales.orders.view',
            'icon' => 'icon eye-icon'
        ]);
    }

    public function prepareMassActions() {
        // $this->addMassAction([
        //     'type' => 'delete',
        //     'action' => route('admin.catalog.attributes.massdelete'),
        //     'method' => 'DELETE'
        // ]);
    }
}