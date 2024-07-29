<?php

namespace Webkul\Product\Repositories;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Webkul\Core\Eloquent\Repository;

class ProductMediaRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model()
    {
        /**
         * This repository is extended to `ProductImageRepository` and `ProductVideoRepository`
         * repository.
         *
         * And currently no model is assigned to this repo.
         */
    }

    /**
     * Get product directory.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     */
    public function getProductDirectory($product): string
    {
        return 'product/'.$product->id;
    }

    /**
     * Upload.
     *
     * @param  array  $data
     * @param  \Webkul\Product\Contracts\Product  $product
     */
    public function upload($data, $product, string $uploadFileType): void
    {
        /**
         * Previous model ids for filtering.
         */
        $previousIds = $this->resolveFileTypeQueryBuilder($product, $uploadFileType)->pluck('id');

        $position = 0;

        if (! empty($data[$uploadFileType]['files'])) {
            foreach ($data[$uploadFileType]['files'] as $indexOrModelId => $file) {
                if ($file instanceof UploadedFile) {
                    if (Str::contains($file->getMimeType(), 'image')) {
                        $manager = new ImageManager;

                        $image = $manager->make($file)->encode('webp');

                        $path = $this->getProductDirectory($product).'/'.Str::random(40).'.webp';

                        Storage::put($path, $image);
                    } else {
                        $path = $file->store($this->getProductDirectory($product));
                    }

                    $this->create([
                        'type'       => $uploadFileType,
                        'path'       => $path,
                        'product_id' => $product->id,
                        'position'   => ++$position,
                    ]);
                } else {
                    if (is_numeric($index = $previousIds->search($indexOrModelId))) {
                        $previousIds->forget($index);
                    }

                    $this->update([
                        'position' => ++$position,
                    ], $indexOrModelId);
                }
            }
        }

        foreach ($previousIds as $indexOrModelId) {
            if (! $model = $this->find($indexOrModelId)) {
                continue;
            }

            Storage::delete($model->path);

            $this->delete($indexOrModelId);
        }
    }

    /**
     * Resolve file type query builder.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return mixed
     *
     * @throws \Exception
     */
    private function resolveFileTypeQueryBuilder($product, string $uploadFileType)
    {
        if ($uploadFileType === 'images') {
            return $product->images();
        } elseif ($uploadFileType === 'videos') {
            return $product->videos();
        }

        throw new Exception('Unsupported file type.');
    }
}
