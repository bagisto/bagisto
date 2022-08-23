<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container;
use Webkul\Product\Repositories\ProductRepository;

class ProductImageRepository extends ProductMediaRepository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        Container $container
    )
    {
        parent::__construct($container);
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
     * Upload images.
     *
     * @param  array  $data
     * @param  \Webkul\Product\Models\Product  $product
     * @return void
     */
    public function uploadImages($data, $product): void
    {
        $this->upload($data, $product, 'images');

        if (isset($data['variants'])) {
            $this->uploadVariantImages($data['variants']);
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
        foreach ($variants as $variantsId => $variantData) {
            $product = $this->productRepository->find($variantsId);

            if (! $product) {
                break;
            }

            $this->upload($variantData, $product, 'images');
        }
    }
}
