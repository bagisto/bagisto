<?php


namespace Webkul\Product\Commands;

use Webkul\Product\Repositories\ProductRepository as Product;

class DemoProducts
{
    /**
     * Holds ProductRepository instance
     */
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * To call the seeder and provide the demo data generators parameters
     */
    public function callGenerator()
    {

        $result = $this->productSeeder();
    }
}