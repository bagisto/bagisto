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
        $queryBuilder = DB::table('products_grid as pg')->addSelect('pg.product_id as product_id', 'pg.sku as product_sku', 'pg.name as product_name', 'pr.type as product_type', 'pg.status as product_status', 'pg.price as product_price', 'pg.quantity as product_qty')->leftJoin('products as pr', 'pg.product_id', '=', 'pr.id');

        $this->addFilters('product_id', 'pg.product_id');
        $this->addFilters('product_sku', 'pg.sku');
        $this->addFilters('product_name', 'pg.name');
        $this->addFilters('product_type', 'pr.type');
        $this->addFilters('product_status', 'pg.status');
        $this->addFilters('product_price', 'pg.price');
        $this->addFilters('product_qty', 'pg.quantity');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'product_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'product_sku',
            'label' => trans('admin::app.datagrid.sku'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'product_name',
            'label' => trans('admin::app.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'product_type',
            'label' => trans('admin::app.datagrid.type'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'product_status',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px',
            'wrapper' => function($value) {
                if ($value == 1)
                    return 'Active';
                else
                    return 'Inactive';
            }
        ]);

        $this->addColumn([
            'index' => 'product_price',
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
            'index' => 'product_qty',
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