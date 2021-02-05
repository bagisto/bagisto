<?php

namespace Webkul\Product;

use Illuminate\Support\Facades\Storage;
use Webkul\Product\Helpers\AbstractProduct;

class ProductVideo extends AbstractProduct
{
    /**
     * Retrieve collection of videos
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @return array
     */
    public function getVideos($product)
    {
        if (! $product) {
            return [];
        }

        $videos = [];

        foreach ($product->videos as $video) {
            if (! Storage::has($video->path)) {
                continue;
            }

            $videos[] = [
                'type' => $video->type,
                'video_url'    => Storage::url($video->path),
            ];
        }

        return $videos;
    }
}