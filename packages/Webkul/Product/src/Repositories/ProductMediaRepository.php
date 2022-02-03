<?php

namespace Webkul\Product\Repositories;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
     * @param  \Webkul\Product\Contracts\Product $product
     * @return string
     */
    public function getProductDirectory($product): string
    {
        return 'product/' . $product->id;
    }

    /**
     * Upload.
     *
     * @param  array  $data
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  string  $uploadFileType
     * @return void
     */
    public function upload($data, $product, string $uploadFileType): void
    {
        /**
         * Previous model ids for filtering.
         */
        $previousIds = $this->resolveFileTypeQueryBuilder($product, $uploadFileType)->pluck('id');

        if (isset($data[$uploadFileType]['files']) && $data[$uploadFileType]['files']) {
            foreach ($data[$uploadFileType]['files'] as $indexOrModelId => $file) {
                if ($file instanceof UploadedFile) {
                    $this->create([
                        'type'       => $uploadFileType,
                        'path'       => $file->store($this->getProductDirectory($product)),
                        'product_id' => $product->id,
                        'position'   => $indexOrModelId,
                    ]);
                } else {
                    /**
                     * Filter out existing models because new model positions are already setuped by index.
                     */
                    if (isset($data[$uploadFileType]['positions']) && $data[$uploadFileType]['positions']) {
                        $positions = collect($data[$uploadFileType]['positions'])->keys()->filter(function ($position) {
                            return is_numeric($position);
                        });

                        $this->update([
                            'position' => $positions->search($indexOrModelId),
                        ], $indexOrModelId);
                    }

                    if (is_numeric($index = $previousIds->search($indexOrModelId))) {
                        $previousIds->forget($index);
                    }
                }
            }
        }

        foreach ($previousIds as $indexOrModelId) {
            if ($model = $this->find($indexOrModelId)) {
                Storage::delete($model->path);

                $this->delete($indexOrModelId);
            }
        }
    }

    /**
     * Resolve file type query builder.
     *
     * @param  \Webkul\Product\Contracts\Product $product
     * @param  string  $uploadFileType
     * @return mixed
     *
     * @throws \Exception
     */
    private function resolveFileTypeQueryBuilder($product, string $uploadFileType)
    {
        if ($uploadFileType === 'images') {
            return $product->images();
        }

        if ($uploadFileType === 'videos') {
            return $product->videos();
        }

        throw new Exception('Unsupported file type.');
    }
}
