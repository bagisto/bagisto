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
        $result = $this->productCreated($product);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Save the product to the product as the product data grid instance
     *
     * @return boolean
     */
    public function productCreated($product) {
        $gridObject = [
            'product_id' => $product->id,
            'sku' => $product->sku,
            'type' => $product->type,
            'attribute_family_name' => $product->toArray()['attribute_family']['name'],
            'name' => $product->name,
            'quantity' => 0,
            'status' => $product->status,
            'price' => $product->price
        ];

        $variants = [];

        $found = $this->productGrid->findOneByField('product_id', $product->id);

        //extra measure to stop duplicate entries
        if ($found == null) {
            $this->productGrid->create($gridObject);

            if ($product->type == 'configurable') {
                $variants = $product->variants()->get();

                foreach ($variants as $variant) {
                    $variantObj = [
                        'product_id' => $variant->id,
                        'sku' => $variant->sku,
                        'type' => $variant->type,
                        'attribute_family_name' => $variant->toArray()['attribute_family']['name'],
                        'name' => $variant->name,
                        'quantity' => 0,
                        'status' => $variant->status,
                        'price' => $variant->price,
                    ];

                    $this->productGrid->create($variantObj);
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
    // public function beforeProductUpdate($productId) {
    //     return true;
    // }

    /**
     * Event handle for after.product.update
     *
     * @return boolean
     */
    public function afterProductUpdate($product) {
        $result = $this->productUpdated($product);

        return $result;
    }

    /**
     * This will be invoked from afterProductUpdate
     *
     * @return boolean
     */
    public function productUpdated($product) {
        $productGridObject = $this->productGrid->findOneByField('product_id', $product->id);

        if (! $productGridObject) {
            return false;
        }

        $gridObject = [
            'product_id' => $product->id,
            'sku' => $product->sku,
            'type' => $product->type,
            'attribute_family_name' => $product->toArray()['attribute_family']['name'],
            'name' => $product->name,
            'status' => $product->status,
        ];

        if ($product->type == 'configurable') {
            $gridObject['quantity'] = 0;
            $gridObject['price'] = 0;
        } else {
            $qty = 0;
            //inventories and inventory sources relation for the variants return empty or null collection objects only
            foreach ($product->inventories()->get() as $inventory_source) {
                $qty = $qty + $inventory_source->qty;
            }

            $gridObject['quantity'] = $qty;
            $gridObject['price'] = $product->price;
        }

        $this->productGrid->update($gridObject, $productGridObject->id);

        if ($product->type == 'configurable') {
            $variants = $product->variants()->get();

            foreach ($variants as $variant) {
                $variantObj = [
                    'product_id' => $variant->id,
                    'sku' => $variant->sku,
                    'type' => $variant->type,
                    'attribute_family_name' => $variant->toArray()['attribute_family']['name'],
                    'name' => $variant->name,
                    'status' => $variant->status,
                    'price' => $variant->price,
                ];

                $qty = 0;

                //inventories and inventory sources relation for the variants return empty or null collection objects only
                foreach ($variant->inventories()->get() as $inventory_source) {
                    $qty = $qty + $inventory_source->qty;
                }

                $variantObj['quantity'] = $qty;

                $qty = 0;

                $productGridVariant = $this->productGrid->findOneByField('product_id', $variant->id);

                if (isset($productGridVariant)) {
                    $this->productGrid->update($variantObj, $productGridVariant->id);
                } else {
                    $variantObj = [
                        'product_id' => $variant->id,
                        'sku' => $variant->sku,
                        'type' => $variant->type,
                        'attribute_family_name' => $variant->toArray()['attribute_family']['name'],
                        'name' => $variant->name,
                        'status' => $variant->status,
                        'price' => $variant->price,
                    ];

                    $qty = 0;

                    //inventories and inventory sources relation for the variants return empty or null collection objects only
                    foreach ($variant->inventories()->get() as $inventory_source) {
                        $qty = $qty + $inventory_source->qty;
                    }

                    $variantObj['quantity'] = $qty;

                    $qty = 0;

                    $this->productGrid->create($variantObj);
                }
            }
        }

        return true;
    }

    /**
     * Manually invoke this function when you have created the products by importing or seeding or factory.
     */
    public function sync() {
        $gridObject = [];

        foreach ($this->product->all() as $product) {
            $gridObject = [
                'product_id' => $product->id,
                'sku' => $product->sku,
                'type' => $product->type,
                'name' => $product->name,
                'attribute_family_name' => $product->toArray()['attribute_family']['name'],
                'price' => $this->price->getMinimalPrice($product),
                'status' => $product->status
            ];

            if ($product->type == 'configurable') {
                $gridObject['quantity'] = 0;
            } else {
                $qty = 0;

                foreach ($product->toArray()['inventories'] as $inventorySource) {
                    $qty = $qty + $inventorySource['qty'];
                }

                $gridObject['quantity'] = $qty;

                $qty = 0;
            }

            $oldGridObject = $this->productGrid->findOneByField('product_id', $product->id);

            if ($oldGridObject) {
                $oldGridObject->update($gridObject);
            } else {
                $this->productGrid->create($gridObject);
            }

            $gridObject = [];
        }

        $this->findRepeated();

        return true;
    }

    public function findRepeated() {

    }
}