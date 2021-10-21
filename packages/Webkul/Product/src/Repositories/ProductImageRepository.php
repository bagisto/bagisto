<?php

namespace Webkul\Product\Repositories;

use Illuminate\Http\UploadedFile;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Container\Container as App;
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
        return 'Webkul\Product\Contracts\ProductImage';
    }

    /**
     * Get product directory.
     *
     * @param  Webkul\Product\Models\Product $variant
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
     * @param  Webkul\Product\Models\Product $product
     * @param  array
     * @return void
     */
    public function upload($product, $images): void
    {
        $previousVariantImageIds = $product->images()->pluck('id');

        if ($images) {
            foreach ($images as $imageId => $image) {
                if ($image instanceof UploadedFile) {
                    $this->create([
                        'path'       => $image->store($this->getProductDirectory($product)),
                        'product_id' => $product->id,
                    ]);
                } else {
                    if (is_numeric($index = $previousVariantImageIds->search($imageId))) {
                        $previousVariantImageIds->forget($index);
                    }
                }
            }
        }

        foreach ($previousVariantImageIds as $imageId) {
            if ($image = $this->find($imageId)) {
                Storage::delete($image->path);

                $this->delete($imageId);
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