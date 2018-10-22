<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;
use Webkul\Channel\Repositories\ChannelRepository;
use Webkul\Product\Repositories\ProductRepository;
/**
 * Product DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class ProductDataGrid
{
    /**
     * The Data Grid implementation @var ProductDataGrid
     * for Products
     */

    public function createProductDataGrid()
    {
        return DataGrid::make([
            'name' => 'Products',
            'table' => 'products_grid as prods',
            'select' => 'prods.id',
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
            ],

            //use aliasing on secodary columns if join is performed

            'columns' => [
                //name, alias, type, label, sortable
                [
                    'name' => 'prods.product_id',
                    'alias' => 'productID',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'prods.sku',
                    'alias' => 'productSku',
                    'type' => 'string',
                    'label' => 'SKU',
                    'sortable' => true,
                ],
                [
                    'name' => 'prods.quantity',
                    'alias' => 'ProductQuantity',
                    'type' => 'string',
                    'label' => 'Product Quantity',
                    'sortable' => false,
                ],
            ],

            'filterable' => [
                //column, type, and label
                // [
                //     'column' => 'prods.id',
                //     'alias' => 'productID',
                //     'type' => 'number',
                //     'label' => 'ID',
                // ],
                // [
                //     'column' => 'prods.sku',
                //     'alias' => 'productCode',
                //     'type' => 'string',
                //     'label' => 'SKU',
                // ],
                // [
                //     'column' => 'attfam.name',
                //     'alias' => 'FamilyName',
                //     'type' => 'string',
                //     'label' => 'Family Name',
                // ],
                // [
                //     'column' => 'pi.qty',
                //     'alias' => 'ProductQuantity',
                //     'type' => 'number',
                //     'label' => 'Product Quatity',
                // ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                //column, type and label
                // [
                //     'column' => 'prods.id',
                //     'type' => 'number',
                //     'label' => 'ID',
                // ],
                // [
                //     'column' => 'prods.sku',
                //     'type' => 'string',
                //     'label' => 'SKU',
                // ],
                // [
                //     'column' => 'attfam.name',
                //     'type' => 'string',
                //     'label' => 'Family Name',
                // ],
                // [
                //     'column' => 'pi.qty',
                //     'type' => 'string',
                //     'label' => 'Product Quatity',
                // ],
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
        // return $this->getProducts();

    }

}