<?php

namespace Webkul\Product;

use Illuminate\Support\Facades\Storage;

class ProductVideo
{
    /**
     * Retrieve collection of videos
     *
     * @param  \Webkul\Product\Contracts\Product  $product
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
                'type'      => $video->type,
                'video_url' => Storage::url($video->path),
            ];
        }

        return $videos;
    }
}
