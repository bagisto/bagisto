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
     * Prepare the data from the product created
     *
     * @return array $data
     */
    public function prepareData($product) {
        $gridObject = [];
        $gridObject = [
            'product_id' => $product->id,
            'sku' => $product->sku,
            'type' => $product->type,
            'attribute_family_name' => $product->attribute_family->name,
        ];

        $variantObjects = [];

        if($this->productGrid->findOneByField('product_id', $product->id)) {
            $gridObject['name'] = $product->name;
            $gridObject['status'] = $product->status;

            if($product->type == 'configurable') {
                $gridObject['quantity'] = 0;
                $gridObject['price'] = $this->price->getMinimalPrice($product);

                $variants = $product->variants;

                if(count($variants)) {
                    foreach($variants as $variant) {
                        $variantObject = [
                            'product_id' => $variant->id,
                            'sku' => $variant->sku,
                            'type' => $variant->type,
                            'attribute_family_name' => $variant->toArray()['attribute_family']['name'],
                            'name' => $variant->name,
                            'status' => $variant->status,
                        ];

                        $qty = 1;

                        foreach($variant->toArray()['inventories'] as $inventorySource) {
                            $qty = $qty + $inventorySource['qty'];
                        }

                        $variantObject['price'] = $product->price;
                        $variantObject['quantity'] = $qty;

                        array_push($variantObjects, $variantObject);

                        $qty = 0;
                    }
                }
            } else {
                $qty = 0;

                foreach($product->toArray()['inventories'] as $inventorySource) {
                    $qty = $qty + $inventorySource['qty'];
                }

                $gridObject['price'] = $product->price;
                $gridObject['quantity'] = $qty;

                $qty = 0;
            }
        }
        // dd($gridObject, $variantObjects);
        return [
            'parent' => $gridObject,
            'variants' => $variantObjects
        ];
    }

    /**
     * Creates a new entry in the product grid whenever a new product is created.
     *
     * @return boolean
     */
    public function afterProductCreated($product) {
        $data = $this->prepareData($product);

        $result = $this->saveProduct($product, $data);

        return $result;
    }

    /**
     * Save the product to the product as the product data grid instance
     *
     * @return boolean
     */
    public function saveProduct($product, $data) {

        $productGridObject = $this->productGrid->findOneByField('product_id', $product->id);
        // dd($product, $data, $productGridObject);
        if (!is_null($productGridObject)) {
            if($product->type == 'simple') {
                $r = $productGridObject->update($data['parent']);
            } else {
                $productGridObject->update($data['parent']);
                if(count($data['variants'])) {
                    dd($data['variants']);
                    foreach($data['variants'] as $variant) {
                        $variantObject = $this->productGrid->findOneByField('product_id', $variant['product_id']);
                        if(!is_null($variantObject)) {
                            $variantObject->update($variant);
                        } else {
                            $this->productGrid->create($variant);
                        }
                    }
                }
            }
        } else {
            $this->productGrid->create($data['parent']);

            //no need for tese lines
            if(count($data['variants'])) {
                foreach($data['variants'] as $variant) {
                    $this->productGrid->create($variant);
                }
            }
        }
        return true;
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