<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * Product Data Grid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderShipmentsDataGrid extends DataGrid
{
    public $allColumns = [];

    public function __construct() {
        $this->itemsPerPage = 10;
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('shipments as ship')->select('ship.id', 'ship.order_id', 'ship.total_qty', 'is.name', 'ors.created_at as orderdate', 'ship.created_at')->addSelect(DB::raw('CONCAT(ors.customer_first_name, " ", ors.customer_last_name) as custname'))->leftJoin('orders as ors', 'ship.order_id', '=', 'ors.id')->leftJoin('inventory_sources as is', 'ship.inventory_source_id', '=', 'is.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function setIndex() {
        $this->index = 'id'; //the column that needs to be treated as index column
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'ship.id',
            'alias' => 'shipId',
            'label' => 'ID',
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'ship.order_id',
            'alias' => 'orderId',
            'label' => 'Order ID',
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'ship.total_qty',
            'alias' => 'shipTotalQty',
            'label' => 'Total Qty',
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
        ]);

        $this->addColumn([
            'index' => 'is.name',
            'alias' => 'shipInventoryName',
            'label' => 'Inventory Source',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
        ]);

        $this->addColumn([
            'index' => 'orderdate',
            'alias' => 'shipOrderDate',
            'label' => 'Order Date',
            'type' => 'datetime',
            'sortable' => true,
            'searchable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'ship.created_at',
            'alias' => 'shipDate',
            'label' => 'Shipment Date',
            'type' => 'datetime',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'custname',
            'alias' => 'shipTO',
            'label' => 'Shipping To',
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
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