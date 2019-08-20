<?php

namespace Webkul\Admin\Listeners;

use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductFlatRepository;

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
     * Product Flat Object
     *
     * Repository Object
     */
    protected $productFlat;

    /**
     * Product Grid Repository Object
     */
    protected $productGrid;

    public function __construct(
        ProductRepository $product,
        ProductFlatRepository $productFlat
    )
    {
        $this->product = $product;

        $this->productFlat = $productFlat;
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
                'price' => $product->getTypeInstance()->getMinimalPrice(),
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
}