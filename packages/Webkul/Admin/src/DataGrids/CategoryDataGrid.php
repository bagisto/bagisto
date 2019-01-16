<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * CategoryDataGrid Class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CategoryDataGrid extends DataGrid
{
    protected $index = 'id'; //the column that needs to be treated as index column

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('categories as cat')->select('cat.id as category_id', 'ct.name as category_name', 'cat.position as category_position', 'cat.status as category_status', 'ct.locale as category_locale')->leftJoin('category_translations as ct', 'cat.id', '=', 'ct.category_id');

        $this->addFilters('category_id', 'custs.id');
        $this->addFilters('category_name', 'ct.name');
        $this->addFilters('category_position', 'cat.position');
        $this->addFilters('category_status', 'cat.status');
        $this->addFilters('category_locale', 'ct.locale');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'category_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'category_name',
            'label' => trans('admin::app.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'category_position',
            'label' => trans('admin::app.datagrid.position'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'category_status',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => true,
            'width' => '100px',
            'wrapper' => function($value) {
                if ($value == 1)
                    return 'Active';
                else
                    return 'Inactive';
            }
        ]);

        $this->addColumn([
            'index' => 'category_locale',
            'label' => trans('admin::app.datagrid.locale'),
            'type' => 'boolean',
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
        // $this->prepareMassAction([
        //     'type' => 'delete',
        //     'action' => route('admin.catalog.products.massdelete'),
        //     'method' => 'DELETE'
        // ]);

        // $this->prepareMassAction([
        //     'type' => 'update',
        //     'action' => route('admin.catalog.products.massupdate'),
        //     'method' => 'PUT',
        //     'options' => [
        //         0 => true,
        //         1 => false,
        //     ]
        // ]);
    }
}