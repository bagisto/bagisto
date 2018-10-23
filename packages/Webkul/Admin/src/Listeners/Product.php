<?php

namespace Webkul\Admin\Listeners;

use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductGridRepository;

use Webkul\Product\Helpers\Price;


/**
 * Products Event handler
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Product {

    /**
     * Product Repository Object
     */
    protected $product;

    /**
     * Price Object
     *
     * @var array
     */
    Protected $price;


    /**
     * Product Grid Repository Object
     */
    protected $productGrid;

    public function __construct(ProductRepository $product, ProductGridRepository $productGrid, Price $price)
    {
        $this->product = $product;

        $this->productGrid = $productGrid;

        $this->price = $price;
    }

    /**
     * Creates a new entry in the product grid whenever a new product is created.
     *
     * @return boolean
     */
    public function afterProductCreated($product) {
        $gridObject = [];

        $gridObject = [
            'product_id' => $product->id,
            'sku' => $product->sku,
            'type' => $product->type,
            'attribute_family_name' => $product->toArray()['attribute_family']['name'],
        ];

        if($this->productGrid->create($gridObject)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Event before the product update
     *
     * @return boolean
     */
    public function beforeProductUpdate($productId) {
        return true;
    }

    /**
     * Event after the product update
     *
     * @var collection product
     *
     * return boolean
     */
    public function afterProductUpdate($product) {
        //update product grid here
        $this->productGrid->updateWhere($product);

        return true;
    }

    /**
     * Event after deletion of the product
     *
     * @return boolean
     */
    public function afterProductDelete($productId) {
        return true;
    }

    /**
     * Fill attributes for that product after the creation
     *
     * @return boolean
     */
    public function fillAttribute() {

    }

    public function sync() {
        $gridObject = [];

        foreach($this->product->all() as $product) {
            $gridObject = [
                'product_id' => $product->id,
                'sku' => $product->sku,
                'type' => $product->type,
                'name' => $product->name,
                'attribute_family_name' => $product->toArray()['attribute_family']['name'],
                'price' => $this->price->getMinimalPrice($product),
                'status' => $product->status
            ];

            if($product->type == 'configurable') {
                $gridObject['quantity'] = 0;
            } else {
                $qty = 0;

                foreach($product->toArray()['inventories'] as $inventorySource) {
                    $qty = $qty + $inventorySource['qty'];
                }

                $gridObject['quantity'] = $qty;

                $qty = 0;
            }
            $this->productGrid->create($gridObject);

            $gridObject = [];
        }

        return true;
    }
}