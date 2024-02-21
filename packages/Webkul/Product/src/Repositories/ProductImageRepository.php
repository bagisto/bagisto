<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container;
use Webkul\Product\Contracts\Product;
use Webkul\Product\Contracts\ProductImage;

class ProductImageRepository extends ProductMediaRepository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ProductImage::class;
    }

    /**
     * Upload images.
     */
    public function uploadImages($data, Product $product): void
    {
        $this->upload($data, $product, 'images');

        if (isset($data['variants'])) {
            $this->uploadVariantImages($data['variants']);
        }
    }

    /**
     * Upload variant images.
     */
    public function uploadVariantImages(array $variants): void
    {
        foreach ($variants as $variantsId => $variantData) {
            $product = $this->productRepository->find($variantsId);

            if (! $product) {
                break;
            }

            $this->upload($variantData, $product, 'images');
        }
    }
}
