<?php

namespace Webkul\Product\Repositories;

use Webkul\Product\Contracts\Product;
use Webkul\Product\Contracts\ProductVideo;

class ProductVideoRepository extends ProductMediaRepository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ProductVideo::class;
    }

    /**
     * Upload videos.
     */
    public function uploadVideos(array $data, Product $product): void
    {
        $this->upload($data, $product, 'videos');
    }
}
