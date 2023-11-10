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
}