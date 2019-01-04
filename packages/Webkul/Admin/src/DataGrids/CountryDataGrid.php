<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;


/**
 * Countries DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CountryDataGrid
{

    /**
     * The Data Grid implementation for countries
     */
    public function createCountryDataGrid()
    {

            return DataGrid::make([
            'name' => 'Countries',
            'table' => 'countries',
            'select' => 'id',
            'perpage' => 10,
            'aliased' => false, //use this with false as default and true in case of joins

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
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to edit this record?',
                    'icon' => 'icon pencil-lg-icon',
                ], [
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
                    'name' => 'id',
                    'alias' => 'countryId',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ], [
                    'name' => 'code',
                    'alias' => 'countryCode',
                    'type' => 'string',
                    'label' => 'Code',
                    'sortable' => true,
                ], [
                    'name' => 'name',
                    'alias' => 'countryName',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => true,
                ], [
                    'name' => 'status',
                    'alias' => 'countryStatus',
                    'type' => 'number',
                    'label' => 'Code',
                    'sortable' => true,
                ],

            ],

            //don't use aliasing in case of filters
            'filterable' => [
                [
                    'column' => 'id',
                    'alias' => 'countryId',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'code',
                    'alias' => 'countryCode',
                    'type' => 'string',
                    'label' => 'Code',
                ], [
                    'column' => 'name',
                    'alias' => 'countryName',
                    'type' => 'string',
                    'label' => 'Name',
                ], [
                    'column' => 'status',
                    'alias' => 'countryStatus',
                    'type' => 'number',
                    'label' => 'Code',
                ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'name',
                    'type' => 'string',
                    'label' => 'Name',
                ], [
                    'column' => 'code',
                    'type' => 'string',
                    'label' => 'Code',
                ], [
                    'column' => 'name',
                    'type' => 'string',
                    'label' => 'Name',
                ], [
                    'column' => 'status',
                    'type' => 'number',
                    'label' => 'Code',
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

        return $this->createCountryDataGrid()->render();

    }
}