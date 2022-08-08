<?php

namespace Webkul\Product;

use Illuminate\Support\Facades\Storage;
use Webkul\Product\Helpers\AbstractProduct;
use Webkul\Product\Repositories\ProductRepository;

class ProductImage extends AbstractProduct
{
    /**
     * Create a new helper instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @return void
     */
    public function __construct(protected ProductRepository $productRepository)
    {
    }

    /**
     * Retrieve collection of gallery images.
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

            $images[] = $this->getCachedImageUrls($image->path);
        }

        if (
            ! $product->parent_id
            && ! count($images)
            && ! count($product->videos)
        ) {
            $images[] = $this->getFallbackImageUrls();
        }

        /*
         * Product parent checked already above. If the case reached here that means the
         * parent is available. So recursing the method for getting the parent image if
         * images of the child are not found.
         */
        if (empty($images)) {
            $images = $this->getGalleryImages($product->parent);
        }

        return $loadedGalleryImages[$product->id] = $images;
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
     * This method will first check whether the gallery images are already
     * present or not. If not then it will load from the product.
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @param  array
     * @return array
     */
    public function getProductBaseImage($product, array $galleryImages = null)
    {
        static $loadedBaseImages = [];

        if (! $product) {
            return;
        }

        if (array_key_exists($product->id, $loadedBaseImages)) {
            return $loadedBaseImages[$product->id];
        }

        return $loadedBaseImages[$product->id] = $galleryImages
            ? $galleryImages[0]
            : $this->otherwiseLoadFromProduct($product);
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

        return $images && $images->count()
            ? $this->getCachedImageUrls($images[0]->path)
            : $this->getFallbackImageUrls();
    }

    /**
     * Get cached urls configured for intervention package.
     *
     * @param  string  $path
     * @return array
     */
    private function getCachedImageUrls($path): array
    {
        if (! $this->isDriverLocal()) {
            return [
                'small_image_url'    => Storage::url($path),
                'medium_image_url'   => Storage::url($path),
                'large_image_url'    => Storage::url($path),
                'original_image_url' => Storage::url($path),
            ];
        }

        return [
            'small_image_url'    => url('cache/small/' . $path),
            'medium_image_url'   => url('cache/medium/' . $path),
            'large_image_url'    => url('cache/large/' . $path),
            'original_image_url' => url('cache/original/' . $path),
        ];
    }

    /**
     * Get fallback urls.
     *
     * @return array
     */
    private function getFallbackImageUrls(): array
    {
        return [
            'small_image_url'    => asset('vendor/webkul/ui/assets/images/product/small-product-placeholder.webp'),
            'medium_image_url'   => asset('vendor/webkul/ui/assets/images/product/meduim-product-placeholder.webp'),
            'large_image_url'    => asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.webp'),
            'original_image_url' => asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.webp'),
        ];
    }

    /**
     * Is driver local.
     *
     * @return bool
     */
    private function isDriverLocal(): bool
    {
        return Storage::getAdapter() instanceof \League\Flysystem\Local\LocalFilesystemAdapter;
    }
}
