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
class OrderInvoicesDataGrid extends DataGrid
{
    public $allColumns = [];

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('invoices')->select('id', 'order_id', 'state', 'grand_total', 'created_at');

        $this->setQueryBuilder($queryBuilder);
    }

    public function setIndex() {
        $this->index = 'id'; //the column that needs to be treated as index column
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'alias' => 'invid',
            'label' => 'ID',
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'order_id',
            'alias' => 'orderId',
            'label' => 'Order ID',
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'grand_total',
            'alias' => 'invgrandtotal',
            'label' => 'Grand Total',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'alias' => 'invcreatedat',
            'label' => 'Invoice Date',
            'type' => 'datetime',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'View',
            'route' => 'admin.sales.invoices.view',
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