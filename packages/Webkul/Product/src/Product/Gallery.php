<?php

namespace Webkul\Product\Product;

use Webkul\Attribute\Repositories\AttributeOptionRepository as AttributeOption;

class Gallery extends AbstractProduct
{
    /**
     * Retrieve collection of gallery images
     *
     * @param Product $product
     * @return array
     */
    public function getImages($product)
    {
        $images[] = [
            'small_image_url' => '',
            'medium_image_url' => '',
            'large_image_url' => '',
        ];

        return $images;
    }
}