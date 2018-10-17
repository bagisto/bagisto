<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * Tax Rates DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class TaxRateDataGrid
{
    /**
     * The Tax Rule Data
     * Grid implementation.
     *
     * @var TaxRateDataGrid
     */
    public function createTaxRateDataGrid()
    {

        return DataGrid::make([
            'name' => 'Tax Rates',
            'table' => 'tax_rates as tr',
            'select' => 'tr.id',
            'perpage' => 10,
            'aliased' => true, //use this with false as default and true in case of joins

            'massoperations' =>[
                [
                    'route' => route('admin.datagrid.delete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button',
                ],
                [
                    'route' => route('admin.datagrid.index'),
                    'method' => 'POST',
                    'label' => 'View Grid',
                    'type' => 'select',
                    'options' =>[
                        1 => 'Edit',
                        2 => 'Set',
                        3 => 'Change Status'
                    ]
                ],
            ],
            'actions' => [
                [
                    'type' => 'Edit',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really wanis?',
                    'icon' => 'icon pencil-lg-icon',
                ],
                [
                    'type' => 'Delete',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to do this?',
                    'icon' => 'icon trash-icon',
                ],
            ],
            'join' => [],

            //use aliasing on secodary columns if join is performed
            'columns' => [
                [
                    'name' => 'tr.id',
                    'alias' => 'id',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'tr.identifier',
                    'alias' => 'identifier',
                    'type' => 'string',
                    'label' => 'Identifier',
                    'sortable' => true,
                    // 'wrapper' => function ($value, $object) {
                    //     return '<a class="color-red">' . $object->identifier . '</a>';
                    // },
                ],
                [
                    'name' => 'tr.state',
                    'alias' => 'state',
                    'type' => 'string',
                    'label' => 'State',
                    'sortable' => true,
                ],
                [
                    'name' => 'tr.country',
                    'alias' => 'country',
                    'type' => 'string',
                    'label' => 'Country',
                    'sortable' => true,
                ],

                [
                    'name' => 'tr.tax_rate',
                    'alias' => 'tax_rate',
                    'type' => 'number',
                    'label' => 'Tax Rate',
                    'sortable' => true,
                ],
            ],
            //don't use aliasing in case of filters
            'filterable' => [
                [
                    'column' => 'tr.id',
                    'alias' => 'ID',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'tr.identifier',
                    'alias' => 'identifier',
                    'type' => 'string',
                    'label' => 'Identifier',
                ], [
                    'column' => 'tr.state',
                    'alias' => 'state',
                    'type' => 'string',
                    'label' => 'State',
                ], [
                    'column' => 'tr.country',
                    'alias' => 'country',
                    'type' => 'string',
                    'label' => 'Country',
                ], [
                    'column' => 'tr.tax_rate',
                    'alias' => 'tax_rate',
                    'type' => 'number',
                    'label' => 'Tax Rate',
                ],
            ],
            //don't use aliasing in case of searchables
            'searchable' => [
                [
                    'column' => 'tr.identifier',
                    'type' => 'string',
                    'label' => 'Identifier',
                ],
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

        return $this->createTaxRateDataGrid()->render();

    }
}