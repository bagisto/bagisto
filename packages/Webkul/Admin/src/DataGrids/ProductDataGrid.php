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
            'select' => 'prods.product_id',
            'perpage' => 10,
            'aliased' => true, //use this with false as default and true in case of joins

            'massoperations' =>[
                //check other grid for configuration and make of your own
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
                    'alias' => 'id',
                    'type' => 'string',
                    'label' => 'ID',
                    'sortable' => true,
                ], [
                    'name' => 'prods.sku',
                    'alias' => 'productSku',
                    'type' => 'string',
                    'label' => 'SKU',
                    'sortable' => true,
                ], [
                    'name' => 'prods.name',
                    'alias' => 'ProductName',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => false,
                ], [
                    'name' => 'prods.type',
                    'alias' => 'ProductType',
                    'type' => 'string',
                    'label' => 'Type',
                    'sortable' => false,
                ], [
                    'name' => 'prods.status',
                    'alias' => 'ProductStatus',
                    'type' => 'string',
                    'label' => 'Status',
                    'sortable' => false,
                    'wrapper' => function ($value) {
                        if($value == 1)
                            return 'Active';
                        else
                            return 'Inactive';
                    },
                ], [
                    'name' => 'prods.price',
                    'alias' => 'ProductPrice',
                    'type' => 'string',
                    'label' => 'Price',
                    'sortable' => false,
                    'wrapper' => function ($value) {
                        return core()->formatBasePrice($value);
                    },
                ], [
                    'name' => 'prods.quantity',
                    'alias' => 'ProductQuantity',
                    'type' => 'string',
                    'label' => 'Product Quantity',
                    'sortable' => false,
                ],
            ],

            'filterable' => [
                //column, alias, type, and label
                [
                    'column' => 'prods.product_id',
                    'alias' => 'productID',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'prods.sku',
                    'alias' => 'productSku',
                    'type' => 'string',
                    'label' => 'SKU',
                ], [
                    'column' => 'prods.name',
                    'alias' => 'ProductName',
                    'type' => 'string',
                    'label' => 'Product Name',
                ], [
                    'column' => 'prods.type',
                    'alias' => 'ProductType',
                    'type' => 'string',
                    'label' => 'Product Type',
                ]
            ],
            //don't use aliasing in case of searchables

            'searchable' => [
                //column, type and label
                [
                    'column' => 'prods.product_id',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'prods.sku',
                    'type' => 'string',
                    'label' => 'SKU',
                ], [
                    'column' => 'prods.name',
                    'type' => 'string',
                    'label' => 'Product Name',
                ], [
                    'column' => 'prods.type',
                    'type' => 'string',
                    'label' => 'Product Type',
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
        return $this->createProductDataGrid()->render();
        // return $this->getProducts();

    }

}