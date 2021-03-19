<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Container\Container as App;
use Webkul\Product\Repositories\ProductRepository;

class ProductImageRepository extends Repository
{
    /**
     * ProductRepository object
     *
     * @return Object
     */
    protected $productRepository;

    /**
     * Create a new repository instance.
     *
     * @param \Webkul\Product\Repositories\ProductRepository $productRepository
     * @param \Illuminate\Container\Container                $app
     *
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

                if ($image instanceof UploadedFile) {
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
                }
            }
        }

        if (isset($data['variants'])) {
            $this->uploadVariantImages($data['variants']);
        }

        foreach ($previousImageIds as $imageId) {
            if ($imageModel = $this->find($imageId)) {
                Storage::delete($imageModel->path);

                $this->delete($imageId);
            }
        }
    }

    /**
     * @param  array $variants
     * @return void
     */
    public function uploadVariantImages($variants)
    {
        foreach ($variants as $variantsId => $variant) {
            $product = $this->productRepository->findOrFail($variantsId);
            $previousVariantImageIds = $product->images()->pluck('id');

            if (isset($variant['images'])) {
                foreach ($variant['images'] as $imageId => $image) {
                    $file = 'variants.' . $product->id  .'.'. 'images.' . $imageId;
                    $dir = 'product/' . $product->id;

                    if ($image instanceof UploadedFile) {
                        if (request()->hasFile($file)) {
                            $this->create([
                                'path'       => request()->file($file)->store($dir),
                                'product_id' => $product->id,
                            ]);
                        }
                    } else {
                        if (is_numeric($index = $previousVariantImageIds->search($imageId))) {
                            $previousVariantImageIds->forget($index);
                        }
                    }
                }
            }

            foreach ($previousVariantImageIds as $imageId) {
                if ($imageModel = $this->find($imageId)) {
                    Storage::delete($imageModel->path);

                    $this->delete($imageId);
                }
            }
        }
    }
}