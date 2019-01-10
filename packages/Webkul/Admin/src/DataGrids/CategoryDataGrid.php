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
class CategoryDataGrid extends AbsGrid
{
    public $allColumns = [];

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('categories as cat')->select('cat.id')->addSelect($this->columns)->leftJoin('category_translations as ct', 'cat.id', '=', 'ct.category_id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'column' => 'cat.id',
            'alias' => 'catId',
            'label' => 'ID',
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'column' => 'ct.name',
            'alias' => 'catName',
            'label' => 'Name',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'cat.position',
            'alias' => 'catPosition',
            'label' => 'Position',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'cat.status',
            'alias' => 'catStatus',
            'label' => 'Type',
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'ct.locale',
            'alias' => 'catLocale',
            'label' => 'Locale',
            'type' => 'boolean',
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

    public function render()
    {
        $this->addColumns();

        $this->prepareActions();

        $this->prepareMassActions();

        $this->prepareQueryBuilder();

        return view('ui::testgrid.table')->with('results', ['records' => $this->getCollection(), 'columns' => $this->allColumns, 'actions' => $this->actions, 'massactions' => $this->massActions]);
    }
}