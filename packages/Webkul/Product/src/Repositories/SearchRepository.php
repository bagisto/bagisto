<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Traits\Sanitizer;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Container\Container as App;
use Webkul\Product\Repositories\ProductRepository;

class SearchRepository extends Repository
{
    use Sanitizer;

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

        $this->sanitizeSVG($path, $data['image']->getMimeType());

        return Storage::url($path);
    }
}