<?php

namespace Webkul\Product;

use Illuminate\Support\Facades\Storage;
use Webkul\Product\Contracts\Product;

class ProductVideo
{
    /**
     * Get the largest video that can be uploaded for a product, in kilobytes, which is the
     * configured limit capped by what PHP itself accepts.
     */
    public function getMaxUploadSize(): int
    {
        $configured = (int) (core()->getConfigData('catalog.products.attribute.file_attribute_upload_size') ?: 10240);

        return min($configured, core()->getMaxUploadSize());
    }

    /**
     * Retrieve collection of videos.
     *
     * @param  Product  $product
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
                'video_url' => Storage::url($video->path),
            ];
        }

        return $videos;
    }
}
