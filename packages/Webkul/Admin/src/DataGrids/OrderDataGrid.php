<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * Order DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class OrderDataGrid
{
    /**
     * The Data Grid implementation.
     *
     * @var AttributeDataGrid
     * for countries
     */

    public function createCategoryDataGrid()
    {

            return DataGrid::make([
            'name' => 'Orders',
            'table' => 'orders as ord',
            'select' => 'ord.id',
            'perpage' => 5,
            'aliased' => true, //use this with false as default and true in case of joins

            'massoperations' =>[
                [
                    'route' => route('admin.datagrid.delete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button',
                ],
            ],

            'actions' => [
                [
                    'type' => 'Edit',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to do this?',
                    'icon' => 'icon pencil-lg-icon',
                ], [
                    'type' => 'Delete',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to do this?',
                    'icon' => 'icon trash-icon',
                ],
            ],

            'join' => [
                [
                    'join' => 'leftjoin',
                    'table' => 'category_translations as ct',
                    'primaryKey' => 'cat.id',
                    'condition' => '=',
                    'secondaryKey' => 'ct.category_id',
                ], [
                    'join' => 'leftjoin',
                    'table' => 'category_translations as cta',
                    'primaryKey' => 'cat.parent_id',
                    'condition' => '=',
                    'secondaryKey' => 'cta.category_id',
                ],
            ],

            //use aliasing on secodary columns if join is performed

            'columns' => [
                [
                    'name' => 'cat.id',
                    'alias' => 'catID',
                    'type' => 'number',
                    'label' => 'Category ID',
                    'sortable' => true,
                ], [
                    'name' => 'ct.name',
                    'alias' => 'catName',
                    'type' => 'string',
                    'label' => 'Category Name',
                    'sortable' => false,
                ], [
                    'name' => 'cat.position',
                    'alias' => 'catPosition',
                    'type' => 'string',
                    'label' => 'Category Position',
                    'sortable' => false,
                ], [
                    'name' => 'cta.name',
                    'alias' => 'parentName',
                    'type' => 'string',
                    'label' => 'Parent Name',
                    'sortable' => true,
                ], [
                    'name' => 'cat.status',
                    'alias' => 'catStatus',
                    'type' => 'string',
                    'label' => 'Visible in Menu',
                    'sortable' => true,
                    'wrapper' => function ($value) {
                        if($value == 0)
                            return "False";
                        else
                            return "True";
                    },
                ],

            ],

            'filterable' => [
                [
                    'column' => 'cat.id',
                    'alias' => 'catID',
                    'type' => 'number',
                    'label' => 'Category ID',
                ], [
                    'column' => 'ct.name',
                    'alias' => 'catName',
                    'type' => 'string',
                    'label' => 'Category Name',
                ], [
                    'column' => 'cta.name',
                    'alias' => 'parentName',
                    'type' => 'string',
                    'label' => 'Parent Name',
                ], [
                    'column' => 'cat.status',
                    'alias' => 'catStatus',
                    'type' => 'string',
                    'label' => 'Visible in Menu',
                ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'cat.id',
                    'type' => 'number',
                    'label' => 'Category ID',
                ], [
                    'column' => 'ct.name',
                    'type' => 'string',
                    'label' => 'Category Name',
                ], [
                    'column' => 'cat.status',
                    'type' => 'string',
                    'label' => 'Visible in Menu',
                ]
            ],

            //list of viable operators that will be used
            'operators' => [
                'eq' => "=",
                'lt' => "<",
                'gt' => ">",
                'lte' => "<=",
                'gte' => ">=",
                'neqs' => "<>",
                'neqn' => "!=",
                'like' => "like",
                'nlike' => "not like",
            ],
            // 'css' => []
        ]);
    }

    public function render()
    {
        return $this->createCategoryDataGrid()->render();

    }
}