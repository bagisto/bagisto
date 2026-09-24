<?php

namespace Webkul\RMA\Repositories;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMAImage;

class RMAImageRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return RMAImage::class;
    }

    /**
     * Manage images.
     */
    public function manageImages($requestImages, $rma): void
    {
        foreach ($requestImages as $itemImage) {
            $this->create([
                'rma_id' => $rma->id,
                'path' => $itemImage->getClientOriginalName(),
            ]);
        }

        $this->uploadImages($requestImages, $rma);
    }

    /**
     * Upload images.
     */
    public function uploadImages(array $requestImages, object $rma): void
    {
        $previousImageIds = $rma->images()->pluck('id');

        if (! empty($requestImages)) {
            foreach ($requestImages as $imageId => $image) {
                $file = 'images.'.$imageId;
                $dir = 'rmas/'.$rma->id.'/images';

                if (request()->hasFile($file)) {
                    $this->create([
                        'path' => request()->file($file)->store($dir, 'private'),
                        'rma_id' => $rma->id,
                    ]);
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
