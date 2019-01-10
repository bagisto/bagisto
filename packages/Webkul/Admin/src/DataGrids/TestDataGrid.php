<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\AbsGrid;
use DB;

/**
 * Product Data Grid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TestDataGrid extends AbsGrid
{
    public $allColumns = [];

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('products_grid')->select('product_id as id')->addSelect($this->columns)->leftJoin('products', 'products_grid.product_id', '=', 'products.id')->where('products.parent_id', '=', null);

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'column' => 'products_grid.product_id',
            'alias' => 'productid',
            'label' => 'ID',
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'column' => 'products_grid.sku',
            'alias' => 'productsku',
            'label' => 'SKU',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'products_grid.name',
            'alias' => 'productname',
            'label' => 'Name',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'products.type',
            'alias' => 'producttype',
            'label' => 'Type',
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'products_grid.status',
            'alias' => 'productstatus',
            'label' => 'Status',
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'products_grid.price',
            'alias' => 'productprice',
            'label' => 'Price',
            'type' => 'number',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'products_grid.quantity',
            'alias' => 'productqty',
            'label' => 'Quantity',
            'type' => 'number',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px'
        ]);
    }

    public function prepareActions() {
        $this->prepareAction([
            'type' => 'Edit',
            'route' => 'admin.catalog.products.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->prepareAction([
            'type' => 'Delete',
            'route' => 'admin.catalog.products.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon' => 'icon trash-icon'
        ]);
    }

    public function prepareMassActions() {
        $this->prepareMassAction([
            'type' => 'delete',
            'action' => route('admin.catalog.products.massdelete'),
            'method' => 'DELETE'
        ]);

        $this->prepareMassAction([
            'type' => 'update',
            'action' => route('admin.catalog.products.massupdate'),
            'method' => 'PUT',
            'options' => [
                0 => true,
                1 => false,
            ]
        ]);
    }

    public function render()
    {
        $this->addColumns();

        $this->prepareActions();

        $this->prepareMassActions();

        $this->prepareQueryBuilder();

        return view('ui::testgrid.table')->with('results', ['records' => $this->getCollection(), 'columns' => $this->allColumns, 'actions' => $this->actions, 'massactions' => $this->massActions]);
    }
}