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
            'table' => 'currency_exchange_rates as cer',
            'select' => 'cer.id',
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
                [
                    'join' => 'leftjoin',
                    'table' => 'currencies as curr',
                    'primaryKey' => 'cer.target_currency',
                    'condition' => '=',
                    'secondaryKey' => 'curr.id',
                ]
            ],

            //use aliasing on secodary columns if join is performed

            'columns' => [

                [
                    'name' => 'cer.id',
                    'alias' => 'exchID',
                    'type' => 'number',
                    'label' => 'Rate ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'curr.name',
                    'alias' => 'currencyname',
                    'type' => 'string',
                    'label' => 'Currency Name',
                    'sortable' => true,
                ],
                [
                    'name' => 'cer.rate',
                    'alias' => 'exchRate',
                    'type' => 'string',
                    'label' => 'Exchange Rate',
                ],
            ],

            //don't use aliasing in case of filters

            'filterable' => [
                [
                    'column' => 'cer.id',
                    'alias' => 'exchId',
                    'type' => 'number',
                    'label' => 'Rate ID',
                ],
                [
                    'column' => 'curr.name',
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