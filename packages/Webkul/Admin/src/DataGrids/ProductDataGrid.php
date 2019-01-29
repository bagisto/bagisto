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
    protected $paginate = true;

    protected $itemsPerPage = 10; //overriding the default items per page

    protected $sortOrder = 'desc'; //asc or desc

    protected $index = 'product_id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('products_grid as pg')
                ->leftJoin('products as pr', 'pg.product_id', '=', 'pr.id')
                ->addSelect('pg.product_id as product_id', 'pg.sku as product_sku', 'pg.name', 'pr.type as product_type', 'pg.status', 'pg.price', 'pg.quantity');

        $this->addFilter('product_id', 'pg.product_id');
        $this->addFilter('product_sku', 'pg.sku');
        $this->addFilter('product_type', 'pr.type');

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
            // 'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'product_sku',
            'label' => trans('admin::app.datagrid.sku'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            // 'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            // 'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'product_type',
            'label' => trans('admin::app.datagrid.type'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            // 'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            // 'width' => '100px',
            'wrapper' => function($value) {
                if ($value->status == 1)
                    return 'Active';
                else
                    return 'Inactive';
            }
        ]);

        $this->addColumn([
            'index' => 'price',
            'label' => trans('admin::app.datagrid.price'),
            'type' => 'price',
            'sortable' => true,
            'searchable' => false,
            // 'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('admin::app.datagrid.qty'),
            'type' => 'number',
            'sortable' => true,
            'searchable' => false,
            // 'width' => '100px'
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

        $this->enableAction = true;
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

        $this->enableMassAction = true;
    }
}