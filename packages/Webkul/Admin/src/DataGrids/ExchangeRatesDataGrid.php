<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * Exchange Rates DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class ExchangeRatesDataGrid
{
    /**
     * The Data Grid implementation.
     *
     * @var ExchangeRatesDataGrid
     * for Exchange Rates
     */


    public function createExchangeRatesDataGrid()
    {

            return DataGrid::make([
            'name' => 'Exchange Rates',
            'table' => 'currency_exchange_rates',
            'select' => 'id',
            'perpage' => 5,
            'aliased' => false, //use this with false as default and true in case of joins

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
                    'confirm_text' => 'Do you really edit this record?',
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
                    'alias' => 'exchID',
                    'type' => 'number',
                    'label' => 'Rate ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'target_currency',
                    'alias' => 'exchTargetCurrency',
                    'type' => 'string',
                    'label' => 'Target Currency',
                    'sortable' => true,
                ],
                [
                    'name' => 'rate',
                    'alias' => 'exchRate',
                    'type' => 'string',
                    'label' => 'Exchange Rate',
                ],

            ],

            //don't use aliasing in case of filters

            'filterable' => [
                [
                    'column' => 'id',
                    'alias' => 'exchId',
                    'type' => 'number',
                    'label' => 'Rate ID',
                ],
                [
                    'column' => 'target_currency',
                    'alias' => 'exchTargetCurrency',
                    'type' => 'string',
                    'label' => 'Target Currency',
                    'sortable' => true,
                ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'target_currency',
                    'type' => 'string',
                    'label' => 'Target Currency',
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
        return $this->createExchangeRatesDataGrid()->render();
    }
}