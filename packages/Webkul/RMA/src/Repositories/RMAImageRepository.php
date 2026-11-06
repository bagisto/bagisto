<?php

namespace Webkul\RMA\Repositories;

use Illuminate\Http\UploadedFile;
use Webkul\Core\Eloquent\Repository;
use Webkul\Core\Helpers\StoredFile;
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
     * Replace the photos of a return with the ones the request carries.
     */
    public function manageImages($requestImages, $rma): void
    {
        $this->uploadImages($requestImages, $rma);
    }

    /**
     * Upload images.
     */
    public function uploadImages(array $requestImages, object $rma): void
    {
        $previousImageIds = $rma->images()->pluck('id');

        if (! empty($requestImages)) {
            foreach ($requestImages as $image) {
                $dir = 'rmas/'.$rma->id.'/images';

                if ($image instanceof UploadedFile) {
                    $this->create([
                        'path' => $image->store($dir, 'private'),
                        'rma_id' => $rma->id,
                    ]);
                }
            }
        }

        foreach ($previousImageIds as $imageId) {
            if ($imageModel = $this->find($imageId)) {
                app(StoredFile::class)->delete($imageModel->path);

                $this->delete($imageId);
            }
        }
    }
}
