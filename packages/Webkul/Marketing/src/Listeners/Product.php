<?php

namespace Webkul\Marketing\Listeners;

use Webkul\Product\Repositories\ProductRepository;

class Product
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected ProductRepository $productRepository)
    {
    }

    /**
     * Before product is created
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function beforeCreate($product)
    {
    }

    /**
     * After product is updated
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterUpdate($product)
    {
    }

    /**
     * Before product is deleted
     *
     * @param  integer  $id
     * @return void
     */
    public function beforeDelete($product)
    {
    }
}