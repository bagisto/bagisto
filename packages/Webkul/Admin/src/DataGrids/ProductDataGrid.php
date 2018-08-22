<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\ProductGrid;
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
     * The Data Grid implementation.
     * @var ProductDataGrid
     * for Products
     */

    public function createProductDataGrid()
    {

        return ProductGrid::make([
            'name' => 'Products',
            'table' => 'products as prods',
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

            'attributeColumns' => [
                'name', 'price'
            ],

            'join' => [

                //for getting name of attrib family.
                [
                    'join' => 'leftjoin',
                    'table' => 'attribute_families as attfam',
                    'primaryKey' => 'prods.attribute_family_id',
                    'condition' => '=',
                    'secondaryKey' => 'attfam.id',
                ],

                //for getting the attribute values.
                [
                    'join' => 'leftjoin',
                    'table' => 'product_attribute_values as pav',
                    'primaryKey' => 'prods.id',
                    'condition' => '=',
                    'secondaryKey' => 'pav.product_id',
                    'withAttributes' => true
                ],

                //for getting the inventory quantity of a product
                [
                    'join' => 'leftjoin',
                    'table' => 'product_inventories as pi',
                    'primaryKey' => 'prods.id',
                    'condition' => '=',
                    'secondaryKey' => 'pi.product_id',
                ],

            ],

            //use aliasing on secodary columns if join is performed

            'columns' => [
                //name, alias, type, label, sortable
                [
                    'name' => 'prods.id',
                    'alias' => 'productID',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'prods.sku',
                    'alias' => 'productCode',
                    'type' => 'string',
                    'label' => 'SKU',
                    'sortable' => true,
                ],
                [
                    'name' => 'attfam.name',
                    'alias' => 'FamilyName',
                    'type' => 'string',
                    'label' => 'Family Name',
                    'sortable' => true,
                ],
                [
                    'name' => 'pi.qty',
                    'alias' => 'ProductQuantity',
                    'type' => 'string',
                    'label' => 'Product Quatity',
                    'sortable' => false,
                ],
                // [
                //     'name' => 'pav.attribute_id',
                //     'alias' => 'AttributeID',
                //     'type' => 'string',
                //     'label' => 'Attribute ID',
                //     'sortable' => false,

                // ],
            ],

            'filterable' => [
                //column, type, and label
                [
                    'name' => 'prods.id',
                    'alias' => 'productID',
                    'type' => 'number',
                    'label' => 'ID',
                ],
                [
                    'name' => 'prods.sku',
                    'alias' => 'productCode',
                    'type' => 'string',
                    'label' => 'SKU',
                ],
                [
                    'name' => 'attfam.name',
                    'alias' => 'FamilyName',
                    'type' => 'string',
                    'label' => 'Family Name',
                ],
                [
                    'name' => 'pi.qty',
                    'alias' => 'ProductQuantity',
                    'type' => 'number',
                    'label' => 'Product Quatity',
                ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                //column, type and label
                [
                    'column' => 'prods.id',
                    'type' => 'number',
                    'label' => 'ID',
                ],
                [
                    'column' => 'prods.sku',
                    'type' => 'string',
                    'label' => 'SKU',
                ],
                [
                    'column' => 'attfam.name',
                    'type' => 'string',
                    'label' => 'Family Name',
                ],
                [
                    'column' => 'pi.qty',
                    'type' => 'string',
                    'label' => 'Product Quatity',
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
        return $this->createProductDataGrid()->render();
        // return $this->getProducts();

    }

}