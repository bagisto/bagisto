<?php

namespace Webkul\Product\Repositories;

class ProductVideoRepository extends ProductMediaRepository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductVideo';
    }

    /**
     * Upload videos.
     *
     * @param  array  $data
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function uploadVideos($data, $product)
    {
        $this->upload($data, $product, 'videos');
    }
}
