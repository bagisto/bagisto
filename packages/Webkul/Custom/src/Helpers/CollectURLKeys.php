<?php

namespace Webkul\Custom\Helpers;

use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductFlatRepository as Product;

class CollectURLKeys
{
    /**
     * Will hold CategoryRepository instance
     */
    protected $category;

     /**
     * Will hold ProductRepository instance
     */
    protected $product;

    public function __construct(
        Category $category,
        Product $product
    )
    {
        $this->category = $category;

        $this->product = $product;
    }

    public function getCategoryKeys()
    {
        $categories = $this->category->all();

        $categoryURLKeys = collect();

        foreach ($categories as $category) {
            if (strtolower($category->slug) != 'root') {
                $categoryURLKeys->push([
                    'id' => $category->id,
                    'name' => $category->name,
                    'url_key' => $category->slug
                ]);
            }
        }

        return $categoryURLKeys;
    }

    public function getProductKeys()
    {
        $products = $this->product->findWhere(
            [
                'status' => 1,
                'visible_individually' => 1,
                'channel' => core()->getCurrentChannel()->code,
                'locale' => core()->getCurrentlocale()->code
            ]
        );

        $productURLKeys = collect();

        foreach ($products as $product) {
            $productURLKeys->push([
                'id' => $product->id,
                'name' => $product->name,
                'url_key' => $product->url_key
            ]);
        }

        return $productURLKeys;
    }
}