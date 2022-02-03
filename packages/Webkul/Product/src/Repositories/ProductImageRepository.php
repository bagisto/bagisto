<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository;

class ProductImageRepository extends Repository
{
    /**
     * Product repository object.
     *
     * @var Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository $productRepository
     * @param  \Illuminate\Container\Container                $app
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        App $app
    ) {
        parent::__construct($app);

        $this->productRepository = $productRepository;
    }

    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return \Webkul\Product\Contracts\ProductImage::class;
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
     * Upload images.
     *
     * @param  array  $data
     * @param  \Webkul\Product\Models\Product  $product
     * @return void
     */
    public function uploadImages($data, $product): void
    {
        $this->upload($product, $data['images'] ?? null);

        if (isset($data['variants'])) {
            $this->uploadVariantImages($data['variants']);
        }
    }

    /**
     * Upload.
     *
     * @param  \Webkul\Product\Contracts\Product $product
     * @param  array
     * @return void
     */
    public function upload($product, $images): void
    {
        /**
         * Previous images ids for filtering.
         */
        $previousImageIds = $product->images()->pluck('id');

        if (
            isset($images['files']) && $images['files']
            && isset($images['positions']) && $images['positions']
        ) {
            /**
             * Filter out existing images because new image positions are already setuped by index.
             */
            $imagePositions = collect($images['positions'])->keys()->filter(function ($imagePosition) {
                return is_numeric($imagePosition);
            });

            foreach ($images['files'] as $indexOrImageId => $image) {
                if ($image instanceof UploadedFile) {
                    $this->create([
                        'path'       => $image->store($this->getProductDirectory($product)),
                        'product_id' => $product->id,
                        'position'   => $indexOrImageId,
                    ]);
                } else {
                    $this->update([
                        'position' => $imagePositions->search($indexOrImageId),
                    ], $indexOrImageId);

                    if (is_numeric($index = $previousImageIds->search($indexOrImageId))) {
                        $previousImageIds->forget($index);
                    }
                }
            }
        }

        foreach ($previousImageIds as $indexOrImageId) {
            if ($image = $this->find($indexOrImageId)) {
                Storage::delete($image->path);

                $this->delete($indexOrImageId);
            }
        }
    }

    /**
     * Upload variant images.
     *
     * @param  array $variants
     * @return void
     */
    public function uploadVariantImages($variants): void
    {
        foreach ($variants as $variantsId => $variant) {
            $product = $this->productRepository->find($variantsId);

            if (! $product) {
                break;
            }

            $this->upload($product, $variant['images'] ?? null);
        }
    }
}
