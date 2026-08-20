<?php

namespace Webkul\Product\Repositories;

use Exception;
use Illuminate\Container\Container;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;
use Webkul\Core\Helpers\MediaFileName;
use Webkul\Product\Contracts\Product;

class ProductMediaRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected MediaFileName $mediaFileName,
        Container $container
    ) {
        parent::__construct($container);
    }

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
     * @param  Product  $product
     */
    public function getProductDirectory($product): string
    {
        return 'product/'.$product->id;
    }

    /**
     * Upload.
     *
     * @param  array  $data
     * @param  Product  $product
     */
    public function upload($data, $product, string $uploadFileType): void
    {
        /**
         * Previous model ids for filtering.
         */
        $previousIds = $this->resolveFileTypeQueryBuilder($product, $uploadFileType)->pluck('id');

        /**
         * Per file seo metadata, keyed the same way as the uploaded files.
         */
        $metaData = $data[$uploadFileType]['meta'] ?? [];

        $position = 0;

        if (! empty($data[$uploadFileType]['files'])) {
            foreach ($data[$uploadFileType]['files'] as $indexOrModelId => $file) {
                $meta = $metaData[$indexOrModelId] ?? [];

                if ($file instanceof UploadedFile) {
                    $path = $this->storeUploadedFile($file, $product, $meta);

                    $existing = is_numeric($index = $previousIds->search($indexOrModelId))
                        ? $this->find($indexOrModelId)
                        : null;

                    if ($existing) {
                        $previousIds->forget($index);

                        Storage::delete($existing->path);

                        $model = $this->update([
                            'path' => $path,
                            'position' => ++$position,
                        ], $indexOrModelId);
                    } else {
                        $model = $this->create([
                            'type' => $uploadFileType,
                            'path' => $path,
                            'product_id' => $product->id,
                            'position' => ++$position,
                        ]);
                    }
                } else {
                    if (is_numeric($index = $previousIds->search($indexOrModelId))) {
                        $previousIds->forget($index);
                    }

                    if (! $model = $this->find($indexOrModelId)) {
                        continue;
                    }

                    $model = $this->update([
                        'path' => $this->mediaFileName->rename($model->path, $meta['file_name'] ?? null),
                        'position' => ++$position,
                    ], $indexOrModelId);
                }

                $this->saveAltText($model, $meta);
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
     * Store a newly uploaded file and return the path it was stored at.
     *
     * Images are always re-encoded to webp, so the requested name only ever dictates the
     * base name and never the resulting file type.
     *
     * @param  Product  $product
     */
    protected function storeUploadedFile(UploadedFile $file, $product, array $meta): string
    {
        $directory = $this->getProductDirectory($product);

        $requestedName = $meta['file_name'] ?? null;

        if (Str::contains($file->getMimeType(), 'image')) {
            $encoded = image_manager()->read($file)->encodeByExtension('webp');

            $path = $this->mediaFileName->resolve($directory, $requestedName, 'webp');

            Storage::put($path, (string) $encoded);

            return $path;
        }

        if (filled($requestedName)) {
            $path = $this->mediaFileName->resolve($directory, $requestedName, $file->getClientOriginalExtension());

            Storage::put($path, $file->get());

            return $path;
        }

        return $file->store($directory);
    }

    /**
     * Save the alt text of the media, for the requested locale.
     *
     * Silently skipped for media that does not carry translations, such as videos.
     *
     * @param  mixed  $model
     */
    protected function saveAltText($model, array $meta): void
    {
        if (
            ! $model
            || ! array_key_exists('alt_text', $meta)
            || ! property_exists($model, 'translatedAttributes')
            || ! in_array('alt_text', $model->translatedAttributes)
        ) {
            return;
        }

        foreach (core()->getRequestedLocaleCodes() as $localeCode) {
            $model->translateOrNew($localeCode)->alt_text = $meta['alt_text'];
        }

        $model->save();
    }

    /**
     * Resolve file type query builder.
     *
     * @param  Product  $product
     * @return mixed
     *
     * @throws Exception
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
