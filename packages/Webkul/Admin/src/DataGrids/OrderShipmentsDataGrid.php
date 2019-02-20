<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * OrderShipmentsDataGrid Class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderShipmentsDataGrid extends DataGrid
{
    protected $index = 'shipment_id';

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('shipments')
                ->leftJoin('order_address as order_address_shipping', function($leftJoin) {
                    $leftJoin->on('order_address_shipping.order_id', '=', 'shipments.order_id')
                        ->where('order_address_shipping.address_type', 'shipping');
                })
                ->leftJoin('orders as ors', 'shipments.order_id', '=', 'ors.id')
                ->leftJoin('inventory_sources as is', 'shipments.inventory_source_id', '=', 'is.id')
                ->select('shipments.id as shipment_id', 'shipments.order_id as shipment_order_id', 'shipments.total_qty as shipment_total_qty', 'is.name as inventory_source_name', 'ors.created_at as orderdate', 'shipments.created_at as shipment_created_at')
                ->addSelect(DB::raw('CONCAT(order_address_shipping.first_name, " ", order_address_shipping.last_name) as shipped_to'));

        $this->addFilter('shipment_id', 'shipments.id');
        $this->addFilter('shipment_order_id', 'shipments.order_id');
        $this->addFilter('shipment_total_qty', 'shipments.total_qty');
        $this->addFilter('inventory_source_name', 'is.name');
        $this->addFilter('orderdate', 'ors.created_at');
        $this->addFilter('shipment_created_at', 'shipments.created_at');
        $this->addFilter('shipped_to', DB::raw('CONCAT(ors.customer_first_name, " ", ors.customer_last_name)'));

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'shipment_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'shipment_order_id',
            'label' => trans('admin::app.datagrid.order-id'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'shipment_total_qty',
            'label' => trans('admin::app.datagrid.total-qty'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'inventory_source_name',
            'label' => trans('admin::app.datagrid.inventory-source'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'orderdate',
            'label' => trans('admin::app.datagrid.order-date'),
            'type' => 'datetime',
            'sortable' => true,
            'searchable' => false,
        ]);

        $this->addColumn([
            'index' => 'shipment_created_at',
            'label' => trans('admin::app.datagrid.shipment-date'),
            'type' => 'datetime',
            'sortable' => true,
            'searchable' => false,
        ]);

        $this->addColumn([
            'index' => 'shipped_to',
            'label' => trans('admin::app.datagrid.shipment-to'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'View',
            'route' => 'admin.sales.shipments.view',
            'icon' => 'icon eye-icon'
        ]);
    }
}