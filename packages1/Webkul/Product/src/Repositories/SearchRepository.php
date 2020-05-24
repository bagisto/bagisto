<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository;

class SearchRepository extends Repository
{
    /**
     * ProductRepository object
     *
     * @return Object
     */
    protected $productRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository $productRepository
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        App $app
    )
    {
        parent::__construct($app);

        $this->productRepository = $productRepository;
    }

    function model()
    {
        return 'Webkul\Product\Contracts\Product';
    }

    public function search($data)
    {
        $products = $this->productRepository->searchProductByAttribute($data['term']);

        return $products;
    }
}