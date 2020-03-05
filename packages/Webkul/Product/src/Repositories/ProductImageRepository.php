<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Str;

class ProductImageRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Contracts\ProductImage';
    }

    /**
     * @param  array  $data
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function uploadImages($data, $product)
    {
        $previousImageIds = $product->images()->pluck('id');

        if (isset($data['images'])) {
            foreach ($data['images'] as $imageId => $image) {
                $file = 'images.' . $imageId;
                $dir = 'product/' . $product->id;

                if (Str::contains($imageId, 'image_')) {
                    if (request()->hasFile($file)) {
                        $this->create([
                            'path'       => request()->file($file)->store($dir),
                            'product_id' => $product->id,
                        ]);
                    }
                } else {
                    if (is_numeric($index = $previousImageIds->search($imageId))) {
                        $previousImageIds->forget($index);
                    }

                    if (request()->hasFile($file)) {
                        if ($imageModel = $this->find($imageId)) {
                            Storage::delete($imageModel->path);
                        }

                        $this->update([
                                'path' => request()->file($file)->store($dir),
                            ], $imageId);
                    }
                }
            }
        }

        foreach ($previousImageIds as $imageId) {
            if ($imageModel = $this->find($imageId)) {
                Storage::delete($imageModel->path);

                $this->delete($imageId);
            }
        }
    }
}