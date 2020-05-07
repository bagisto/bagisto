<?php

namespace Webkul\Bulkupload\Repositories;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;

/**
 * Product Image Reposotory
 *
 * @author    Prateek Srivastava <prateek.srivastava781@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
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
     * @param array $data
     * @param mixed $product
     * @return mixed
     */
    public function uploadImages($data, $product)
    {
        $previousImageIds = $product->images()->pluck('id');

        if (isset($data['images'])) {
            foreach ($data['images'] as $imageId => $image) {
                $file = 'images.' . $imageId;
                $dir = 'product/' . $product->id;

                if (str_contains($imageId, 'image_')) {
                    if (request()->hasFile($file)) {
                        $this->create([
                                'path' => request()->file($file)->store($dir),
                                'product_id' => $product->id
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
                                'path' => request()->file($file)->store($dir)
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

    public function bulkuploadImages($data, $product, $imageZipName)
    {
        if (isset($data['images'])) {
            foreach($data['images'] as $key => $value) {
                if(strpos($value, 'admin')) {
                    $files = "imported-products/extracted-images/admin/".$data['dataFlowProfileRecordId'].'/'. $imageZipName['dirname'].'/'.basename($value);

                    $destination = "product/".$product->id.'/'.basename($value);

                    Storage::copy($files, $destination);
                } else {
                    $files = "imported-products/extracted-images/".$data['dataFlowProfileRecordId'].'/'. $imageZipName['dirname'].'/'.basename($value);

                    $destination = "product/".$product->id.'/'.basename($value);

                    Storage::copy($files, $destination);
                }

                $this->create([
                    'path' => 'product/' . $product->id .'/'. basename($value),
                    'product_id' => $product->id
                ]);
            }
        }
    }
}