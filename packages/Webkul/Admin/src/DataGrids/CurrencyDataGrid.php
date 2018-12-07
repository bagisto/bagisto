<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * Currencies DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class CurrencyDataGrid
{
    /**
     * The Data Grid implementation.
     *
     * @var CurrenciesDataGrid
     * for countries
     */

    public function createCurrencyDataGrid()
    {

        return DataGrid::make([
            'name' => 'Currencies',
            'table' => 'currencies',
            'select' => 'id',
            'perpage' => 10,
            'aliased' => false, //use this with false as default and true in case of joins

            'massoperations' =>[
                0 => [
                    'type' => 'delete', //all lower case will be shifted in the configuration file for better control and increased fault tolerance
                    'action' => route('admin.currencies.massdelete'),
                    'method' => 'DELETE'
                ]
            ],

            'actions' => [
                [
                    'type' => 'Edit',
                    'route' => 'admin.currencies.edit',
                    // 'confirm_text' => 'Do you really want to edit this record?',
                    'icon' => 'icon pencil-lg-icon',
                ], [
                    'type' => 'Delete',
                    'route' => 'admin.currencies.delete',
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
                    'alias' => 'currencyId',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ], [
                    'name' => 'code',
                    'alias' => 'currencyCode',
                    'type' => 'string',
                    'label' => 'Code',
                    'sortable' => true,
                ], [
                    'name' => 'name',
                    'alias' => 'currencyName',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => true,
                ],

            ],

            //don't use aliasing in case of filters

            'filterable' => [
                [
                    'column' => 'id',
                    'alias' => 'currencyId',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'code',
                    'alias' => 'currencyCode',
                    'type' => 'string',
                    'label' => 'Code',
                ], [
                    'column' => 'name',
                    'alias' => 'currencyName',
                    'type' => 'string',
                    'label' => 'Name',
                ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'id',
                    'alias' => 'currencyId',
                    'type' => 'number',
                ], [
                    'column' => 'name',
                    'type' => 'string',
                    'label' => 'Name',
                ], [
                    'column' => 'code',
                    'type' => 'string',
                    'label' => 'Code',
                ],
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
        return $this->createCurrencyDataGrid()->render();
    }
}