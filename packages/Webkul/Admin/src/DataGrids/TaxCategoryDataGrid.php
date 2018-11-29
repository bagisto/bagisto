<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * Tax Category DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class TaxCategoryDataGrid
{
    /**
     * The Tax Category Data
     * Grid implementation.
     *
     * @var TaxCategoryDataGrid
     */
    public function createTaxCategoryDataGrid()
    {

        return DataGrid::make([

            'name' => 'Tax Category',
            'table' => 'tax_categories as tr',
            'select' => 'tr.id',
            'perpage' => 10,
            'aliased' => true, //use this with false as default and true in case of joins

            'massoperations' =>[
                // [
                //     'route' => route('admin.datagrid.delete'),
                //     'method' => 'DELETE',
                //     'label' => 'Delete',
                //     'type' => 'button',
                // ],
                // [
                //     'route' => route('admin.datagrid.index'),
                //     'method' => 'POST',
                //     'label' => 'View Grid',
                //     'type' => 'select',
                //     'options' =>[
                //         1 => 'Edit',
                //         2 => 'Set',
                //         3 => 'Change Status'
                //     ]
                // ],
            ],
            'actions' => [
                [
                    'type' => 'Edit',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to edit this record?',
                    'icon' => 'icon pencil-lg-icon',
                ],
                [
                    'type' => 'Delete',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to delete this record?',
                    'icon' => 'icon trash-icon',
                ],
            ],
            'join' => [
                // [
                //     'join' => 'leftjoin',
                //     'table' => 'roles as r',
                //     'primaryKey' => 'u.role_id',
                //     'condition' => '=',
                //     'secondaryKey' => 'r.id',
                // ]
            ],

            //use aliasing on secodary columns if join is performed
            'columns' => [
                [
                    'name' => 'tr.id',
                    'alias' => 'ID',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'tr.name',
                    'alias' => 'Name',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => true,
                    // 'wrapper' => function ($value, $object) {
                    //     return '<a class="color-red">' . $object->Name . '</a>';
                    // },
                ],
                [
                    'name' => 'tr.code',
                    'alias' => 'code',
                    'type' => 'string',
                    'label' => 'Code',
                    'sortable' => true,
                ],
            ],
            //don't use aliasing in case of filters
            'filterable' => [
                [
                    'column' => 'tr.id',
                    'alias' => 'ID',
                    'type' => 'number',
                    'label' => 'ID'
                ],
                [
                    'column' => 'tr.name',
                    'alias' => 'Name',
                    'type' => 'string',
                    'label' => 'Name'
                ],
                [
                    'column' => 'tr.code',
                    'alias' => 'code',
                    'type' => 'string',
                    'label' => 'Code'
                ]
            ],
            //don't use aliasing in case of searchables
            'searchable' => [
                [
                    'column' => 'tr.code',
                    'type' => 'string',
                    'label' => 'Code'
                ],
                [
                    'column' => 'tr.name',
                    'type' => 'string',
                    'label' => 'Name'
                ]
            ],
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

    public function render() {

        return $this->createTaxCategoryDataGrid()->render();

    }
}