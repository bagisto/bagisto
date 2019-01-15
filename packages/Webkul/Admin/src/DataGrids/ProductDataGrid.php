<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * ProductDataGrid Class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductDataGrid extends DataGrid
{
    protected $index = 'product_id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('products_grid as pg')->addSelect('pg.product_id', 'pg.sku', 'pg.name', 'pr.type', 'pg.status', 'pg.price', 'pg.quantity')->leftJoin('products as pr', 'pg.product_id', '=', 'pr.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'pg.product_id',
            'identifier' => 'product_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'pg.sku',
            'identifier' => 'product_sku',
            'label' => trans('admin::app.datagrid.sku'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'pg.name',
            'identifier' => 'product_name',
            'label' => trans('admin::app.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'pr.type',
            'identifier' => 'product_type',
            'label' => trans('admin::app.datagrid.type'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'pg.status',
            'identifier' => 'product_status',
            'label' => trans('admin::app.datagrid.status'),
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
            'index' => 'pg.price',
            'identifier' => 'product_price',
            'label' => trans('admin::app.datagrid.price'),
            'type' => 'number',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px',
            'wrapper' => function($value) {
                return core()->formatBasePrice($value);
            }
        ]);

        $this->addColumn([
            'index' => 'pg.quantity',
            'identifier' => 'product_quantity',
            'label' => trans('admin::app.datagrid.qty'),
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
            'label' => 'Delete',
            'action' => route('admin.catalog.products.massdelete'),
            'method' => 'DELETE'
        ]);

        $this->addMassAction([
            'type' => 'update',
            'label' => 'Update Status',
            'action' => route('admin.catalog.products.massupdate'),
            'method' => 'PUT',
            'options' => [
                'Active' => 1,
                'Inactive' => 0
            ]
        ]);
    }
}