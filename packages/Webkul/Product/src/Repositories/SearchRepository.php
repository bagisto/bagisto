<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Storage;
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
     * @param \Webkul\Product\Repositories\ProductRepository $productRepository
     * @param \Illuminate\Container\Container                $app
     *
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        App $app
    ) {
        parent::__construct($app);

        $this->productRepository = $productRepository;
    }

    function model()
    {
        return 'Webkul\Product\Contracts\Product';
    }

    public function search($data)
    {
        return $this->productRepository->searchProductByAttribute($data['term'] ?? '');
    }

    /**
     * @param  array  $data
     * @return void
     */
    public function uploadSearchImage($data)
    {
        $path = request()->file('image')->store('product-search');

        return Storage::url($path);
    }
}