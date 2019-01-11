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
class ProductDataGrid extends DataGrid
{
    public $allColumns = [];

    public function __construct() {
        $this->itemsPerPage = 10;
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('products_grid')->addSelect('products_grid.product_id', 'products_grid.sku', 'products_grid.name', 'products.type', 'products_grid.status', 'products_grid.price', 'products_grid.quantity')->leftJoin('products', 'products_grid.product_id', '=', 'products.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function setIndex() {
        $this->index = 'product_id'; //the column that needs to be treated as index column
    }

    // public function setGridName() {
    //     $this->gridName = 'products_grid'; // should be the table name for getting proper index
    // }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'products_grid.product_id',
            'alias' => 'productid',
            'label' => 'ID',
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'products_grid.sku',
            'alias' => 'productsku',
            'label' => 'SKU',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'products_grid.name',
            'alias' => 'productname',
            'label' => 'Name',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'products.type',
            'alias' => 'producttype',
            'label' => 'Type',
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'products_grid.status',
            'alias' => 'productstatus',
            'label' => 'Status',
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px',
            'wrapper' => function($value) {
                if($value == 1)
                    return 'Active';
                else
                    return 'Inactive';
            }
        ]);

        $this->addColumn([
            'index' => 'products_grid.price',
            'alias' => 'productprice',
            'label' => 'Price',
            'type' => 'number',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px',
            'wrapper' => function($value) {
                return core()->formatBasePrice($value);
            }
        ]);

        $this->addColumn([
            'index' => 'products_grid.quantity',
            'alias' => 'productqty',
            'label' => 'Quantity',
            'type' => 'number',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px'
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'Edit',
            'route' => 'admin.catalog.products.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'route' => 'admin.catalog.products.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon' => 'icon trash-icon'
        ]);
    }

    public function prepareMassActions() {
        $this->addMassAction([
            'type' => 'delete',
            'action' => route('admin.catalog.products.massdelete'),
            'method' => 'DELETE'
        ]);

        $this->addMassAction([
            'type' => 'update',
            'action' => route('admin.catalog.products.massupdate'),
            'method' => 'PUT',
            'options' => [
                'Active' => 1,
                'Inactive' => 0
            ]
        ]);
    }
}