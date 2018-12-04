<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;


/**
 * Category DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class CategoryDataGrid
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
            'name' => 'Categories',
            'table' => 'categories as cat',
            'select' => 'cat.id',
            'perpage' => 10,
            'aliased' => true, //use this with false as default and true in case of joins

            'massoperations' =>[
                // [
                //     'route' => route('admin.datagrid.delete'),
                //     'method' => 'DELETE',
                //     'label' => 'Delete',
                //     'type' => 'button',
                // ],
            ],

            'actions' => [
                [
                    'type' => 'Edit',
                    'route' => 'admin.catalog.categories.edit',
                    'confirm_text' => 'Do you really want to edit this record?',
                    'icon' => 'icon pencil-lg-icon',
                ], [
                    'type' => 'Delete',
                    'route' => 'admin.catalog.categories.delete',
                    'confirm_text' => 'Do you really want to delete this record?',
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
                ],
            ],

            //use aliasing on secodary columns if join is performed

            'columns' => [
                [
                    'name' => 'cat.id',
                    'alias' => 'catid',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ], [
                    'name' => 'ct.name',
                    'alias' => 'catname',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => true,
                ], [
                    'name' => 'cat.position',
                    'alias' => 'catposition',
                    'type' => 'string',
                    'label' => 'Position',
                    'sortable' => true,
                ], [
                    'name' => 'cat.status',
                    'alias' => 'catstatus',
                    'type' => 'string',
                    'label' => 'Visible in Menu',
                    'sortable' => true,
                    'wrapper' => function ($value) {
                        if($value == 0)
                            return "False";
                        else
                            return "True";
                    },
                ], [
                    'name' => 'ct.locale',
                    'alias' => 'catlocale',
                    'type' => 'string',
                    'label' => 'Locale',
                    'sortable' => true,
                    'filter' => [
                        'function' => 'orWhere',
                        'condition' => ['ct.locale', app()->getLocale()]
                    ],
                ]
            ],

            'filterable' => [
                [
                    'column' => 'cat.id',
                    'alias' => 'catid',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'ct.name',
                    'alias' => 'catname',
                    'type' => 'string',
                    'label' => 'Name',
                ], [
                    'column' => 'cat.status',
                    'alias' => 'catstatus',
                    'type' => 'string',
                    'label' => 'Visible in Menu',
                ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'cat.id',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'ct.name',
                    'type' => 'string',
                    'label' => 'Name',
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