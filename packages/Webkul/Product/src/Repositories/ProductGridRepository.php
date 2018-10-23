<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Helpers\Price;

/**
 * Product Repository
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductGridRepository extends Repository
{
    protected $product;

    /**
     * Price Object
     *
     * @var array
     */
    Protected $price;


    public function __construct(
        Product $product,
        Price $price,
        App $app
    )
    {
        $this->product = $product;

        $this->price = $price;

        parent::__construct($app);
    }

    public function model() {
        return 'Webkul\Product\Models\ProductGrid';
    }

    public function updateWhere($product) {
        if($product->type == "simple") {
            $gridObject = [
                'sku' => $product->sku,
                'name' => $product->name,
                'attribute_family_name' => $product->toArray()['attribute_family']['name'],
                'price' => $this->price->getMinimalPrice($product),
                'status' => $product->status
            ];

            $qty = 0;

            if($product->parent_id == 'null') {
                $gridObject['type'] = $product->type;
            }

            foreach($product->toArray()['inventories'] as $inventorySource) {
                $qty = $qty + $inventorySource['qty'];
            }

            $gridObject['quantity'] = $qty;

            return $this->getModel()->where('product_id', $product->id)->update($gridObject);

        } else if($product->type == "configurable") {
            $gridObject = [
                'sku' => $product->sku,
                'type' => $product->type,
                'name' => $product->name,
                'attribute_family_name' => $product->toArray()['attribute_family']['name'],
                'price' => $this->price->getMinimalPrice($product),
                'status' => $product->status
            ];
            $qty = 0;

            $gridObject['quantity'] = $qty;

            $this->getModel()->where('product_id', $product->id)->update($gridObject);

            $variants = $product->variants;

            foreach($variants as $variant) {
                $gridObject = [];

                $gridObject = [
                    'sku' => $variant->sku,
                    'name' => $variant->name,
                    'attribute_family_name' => $variant->toArray()['attribute_family']['name'],
                    'price' => $this->price->getMinimalPrice($variant),
                    'status' => $variant->status
                ];

                if($variant->type == 'configurable') {
                    $gridObject['quantity'] = 0;
                } else {
                    $qty = 0;

                    foreach($variant->toArray()['inventories'] as $inventorySource) {
                        $qty = $qty + $inventorySource['qty'];
                    }

                    $gridObject['quantity'] = $qty;
                }

                return $this->getModel()->where('product_id', $product->id)->update($gridObject);
            }
        }
        return false;
    }
}