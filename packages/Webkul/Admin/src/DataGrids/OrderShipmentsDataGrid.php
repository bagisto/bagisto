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
    protected $paginate = true;

    protected $itemsPerPage = 5; //overriding the default items per page

    protected $index = 'shipment_id';

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('shipments as ship')
                ->leftJoin('orders as ors', 'ship.order_id', '=', 'ors.id')
                ->leftJoin('inventory_sources as is', 'ship.inventory_source_id', '=', 'is.id')
                ->select('ship.id as shipment_id', 'ship.order_id as shipment_order_id', 'ship.total_qty as shipment_total_qty', 'is.name as inventory_source_name', 'ors.created_at as orderdate', 'ship.created_at as shipment_created_at')
                ->addSelect(DB::raw('CONCAT(ors.customer_first_name, " ", ors.customer_last_name) as custname'));

        $this->addFilter('shipment_id', 'ship.id');
        $this->addFilter('shipment_order_id', 'ship.order_id');
        $this->addFilter('shipment_total_qty', 'ship.total_qty');
        $this->addFilter('inventory_source_name', 'is.name');
        $this->addFilter('orderdate', 'ors.created_at');
        $this->addFilter('shipment_created_at', 'ship.created_at');
        $this->addFilter('custname', DB::raw('CONCAT(ors.customer_first_name, " ", ors.customer_last_name)'));

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
            'index' => 'custname',
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

    public function prepareMassActions() {
        // $this->addMassAction([
        //     'type' => 'delete',
        //     'action' => route('admin.catalog.attributes.massdelete'),
        //     'method' => 'DELETE'
        // ]);
    }
}