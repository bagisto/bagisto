<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;


/**
 * Product DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class ProductDataGrid
{
    /**
     * The Data Grid implementation.
     * @var ProductDataGrid
     * for Products
     */

    public function createProductDataGrid()
    {

            return DataGrid::make([
            'name' => 'Products',
            'table' => 'products as pr',
            'select' => 'pr.id',
            'perpage' => 10,
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
                // [
                //     'join' => 'leftjoin',
                //     'table' => 'category_translations as ct',
                //     'primaryKey' => 'cat.id',
                //     'condition' => '=',
                //     'secondaryKey' => 'ct.category_id',
                // ], [
                //     'join' => 'leftjoin',
                //     'table' => 'category_translations as cta',
                //     'primaryKey' => 'cat.parent_id',
                //     'condition' => '=',
                //     'secondaryKey' => 'cta.category_id',
                // ],

            ],

            //use aliasing on secodary columns if join is performed

            'columns' => [
                //name, alias, type, label, sortable
                [
                    'name' => 'pr.id',
                    'alias' => 'productID',
                    'type' => 'number',
                    'label' => 'Product ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'pr.sku',
                    'alias' => 'productCode',
                    'type' => 'number',
                    'label' => 'Product Code',
                    'sortable' => true,
                ],
            ],

            'filterable' => [

            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                //column, type and label
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

        return $this->createProductDataGrid()->render();

    }
}