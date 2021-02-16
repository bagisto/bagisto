<?php

namespace Webkul\Product;

use Illuminate\Support\Facades\Storage;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Helpers\AbstractProduct;

class ProductImage extends AbstractProduct
{
    /**
     * ProductRepository instance
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * Create a new helper instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository
    )
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Retrieve collection of gallery images
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @return array
     */
    public function getGalleryImages($product)
    {
        static $loadedGalleryImages = [];

        if (! $product) {
            return [];
        }

        if (array_key_exists($product->id, $loadedGalleryImages)) {
            return $loadedGalleryImages[$product->id];
        }

        $images = [];

        foreach ($product->images as $image) {
            if (! Storage::has($image->path)) {
                continue;
            }

            $images[] = [
                'small_image_url'    => url('cache/small/' . $image->path),
                'medium_image_url'   => url('cache/medium/' . $image->path),
                'large_image_url'    => url('cache/large/' . $image->path),
                'original_image_url' => url('cache/original/' . $image->path),
            ];
        }

        if (! $product->parent_id && ! count($images) && ! count($product->videos)) {
            $images[] = [
                'small_image_url'    => asset('vendor/webkul/ui/assets/images/product/small-product-placeholder.webp'),
                'medium_image_url'   => asset('vendor/webkul/ui/assets/images/product/meduim-product-placeholder.webp'),
                'large_image_url'    => asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.webp'),
                'original_image_url' => asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.webp'),
            ];
        }

        return $loadedGalleryImages[$product->id] = $images;
    }

    /**
     * This method will first check whether the gallery images are already
     * present or not. If not then it will load from the product.
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @param array
     * @return array
     */
    public function getProductBaseImage($product, array $galleryImages = null)
    {
        static $loadedBaseImages = [];

        if ($product) {
            if (array_key_exists($product->id, $loadedBaseImages)) {
                return $loadedBaseImages[$product->id];
            }

            return $loadedBaseImages[$product->id] = $galleryImages
                ? $galleryImages[0]
                : $this->otherwiseLoadFromProduct($product);
        }
    }

    /**
     * Get product varient image if available otherwise product base image.
     *
     * @param  \Webkul\Customer\Contracts\Wishlist  $item
     * @return array
     */
    public function getProductImage($item)
    {
        if ($item instanceof \Webkul\Customer\Contracts\Wishlist) {
            if (isset($item->additional['selected_configurable_option'])) {
                $product = $this->productRepository->find($item->additional['selected_configurable_option']);
            } else {
                $product = $item->product;
            }
        } else {
            $product = $item->product;
        }

        return $this->getProductBaseImage($product);
    }

     /**
     * Load product's base image.
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @return array
     */
    protected function otherwiseLoadFromProduct($product)
    {
        $images = $product ? $product->images : null;

        if ($images && $images->count()) {
            $image = [
                'small_image_url'    => url('cache/small/' . $images[0]->path),
                'medium_image_url'   => url('cache/medium/' . $images[0]->path),
                'large_image_url'    => url('cache/large/' . $images[0]->path),
                'original_image_url' => url('cache/original/' . $images[0]->path),
            ];
        } else {
            $image = [
                'small_image_url'    => asset('vendor/webkul/ui/assets/images/product/small-product-placeholder.webp'),
                'medium_image_url'   => asset('vendor/webkul/ui/assets/images/product/meduim-product-placeholder.webp'),
                'large_image_url'    => asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.webp'),
                'original_image_url' => asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.webp'),
            ];
        }

        return $image;
    }
}