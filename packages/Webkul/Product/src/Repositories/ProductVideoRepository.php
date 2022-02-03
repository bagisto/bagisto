<?php

namespace Webkul\Product\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;

class ProductVideoRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model()
    {
        return \Webkul\Product\Contracts\ProductVideo::class;
    }

    /**
     * Get product directory.
     *
     * @param  \Webkul\Product\Contracts\Product $product
     * @return string
     */
    public function getProductDirectory($product): string
    {
        return 'product/' . $product->id;
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
        /**
         * Previous images ids for filtering.
         */
        $previousVideoIds = $product->videos()->pluck('id');

        if (
            isset($data['videos']['files']) && $data['videos']['files']
            && isset($data['videos']['positions']) && $data['videos']['positions']
        ) {
            /**
             * Filter out existing videos because new video positions are already setuped by index.
             */
            $videoPositions = collect($data['videos']['positions'])->keys()->filter(function ($videoPosition) {
                return is_numeric($videoPosition);
            });

            foreach ($data['videos']['files'] as $indexOrVideoId => $video) {
                if ($video instanceof UploadedFile) {
                    $this->create([
                        'path'       => $video->store($this->getProductDirectory($product)),
                        'product_id' => $product->id,
                        'position'   => $indexOrVideoId,
                        'type'       => 'video',
                    ]);
                } else {
                    $this->update([
                        'position' => $videoPositions->search($indexOrVideoId),
                    ], $indexOrVideoId);

                    if (is_numeric($index = $previousVideoIds->search($indexOrVideoId))) {
                        $previousVideoIds->forget($index);
                    }
                }
            }
        }

        foreach ($previousVideoIds as $indexOrVideoId) {
            if ($video = $this->find($indexOrVideoId)) {
                Storage::delete($video->path);

                $this->delete($indexOrVideoId);
            }
        }
    }
}
