<?php

namespace Webkul\Product;

use Illuminate\Support\Facades\Storage;
use Webkul\Customer\Contracts\Wishlist;
use Webkul\Product\Contracts\Product;
use Webkul\Product\Repositories\ProductRepository;

class ProductImage
{
    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(protected ProductRepository $productRepository) {}

    /**
     * Retrieve collection of gallery images.
     *
     * A variant with no images of its own falls back to the ones its parent carries.
     *
     * @param  Product  $product
     * @return array
     */
    public function getGalleryImages($product)
    {
        if (! $product) {
            return [];
        }

        $images = [];

        foreach ($product->images as $image) {
            if (! Storage::has($image->path)) {
                continue;
            }

            $images[] = $this->getCachedImageUrls($image->path, $this->resolveAltText($image, $product, count($images)));
        }

        if (
            ! $product->parent_id
            && ! count($images)
            && ! count($product->videos ?? [])
        ) {
            $images[] = $this->getFallbackImageUrls($product?->name);
        }

        if (empty($images)) {
            $images = $this->getGalleryImages($product->parent);
        }

        return $images;
    }

    /**
     * Get product variant image if available otherwise product base image.
     *
     * @param  Wishlist  $item
     * @return array
     */
    public function getProductImage($item)
    {
        if ($item instanceof Wishlist) {
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
     * @param  Product  $product
     * @return array
     */
    public function getProductBaseImage($product, ?array $galleryImages = null)
    {
        if (! $product) {
            return;
        }

        return $galleryImages
            ? $galleryImages[0]
            : $this->otherwiseLoadFromProduct($product);
    }

    /**
     * Load product's base image.
     *
     * @param  Product  $product
     * @return array
     */
    protected function otherwiseLoadFromProduct($product)
    {
        $images = $product?->images;

        return $images && $images->count()
            ? $this->getCachedImageUrls($images[0]->path, $this->resolveAltText($images[0], $product, 0))
            : $this->getFallbackImageUrls($product?->name);
    }

    /**
     * Resolve the alt text of an image, falling back to the product name so that a
     * storefront image is never rendered without one.
     *
     * @param  Contracts\ProductImage  $image
     * @param  Product  $product
     */
    private function resolveAltText($image, $product, int $index): string
    {
        if (filled($altText = $image->alt_text)) {
            return $altText;
        }

        $name = (string) $product?->name;

        return $index > 0
            ? trim($name.' - '.($index + 1))
            : $name;
    }

    /**
     * Get the urls an image is served from, in every size.
     *
     * The image cache route reads through the configured disk, so a resized copy is
     * offered whichever disk the store keeps its files on.
     *
     * @param  string  $path
     */
    private function getCachedImageUrls($path, string $altText = ''): array
    {
        return [
            'small_image_url' => url('cache/small/'.$path),
            'medium_image_url' => url('cache/medium/'.$path),
            'large_image_url' => url('cache/large/'.$path),
            'original_image_url' => url('cache/original/'.$path),
            'alt' => $altText,
        ];
    }

    /**
     * The placeholder shown in place of an image the product does not have.
     *
     * A store may nominate its own, which is held on the configured disk; otherwise
     * the one the theme ships with is used.
     */
    private function placeholderUrl(string $size): string
    {
        $configured = core()->getConfigData('catalog.products.cache_'.$size.'_image.url');

        return $configured
            ? Storage::url($configured)
            : bagisto_asset('images/'.$size.'-product-placeholder.webp', 'shop');
    }

    /**
     * Get fallback urls.
     */
    private function getFallbackImageUrls(?string $altText = ''): array
    {
        return [
            'small_image_url' => $this->placeholderUrl('small'),
            'medium_image_url' => $this->placeholderUrl('medium'),
            'large_image_url' => $this->placeholderUrl('large'),
            'original_image_url' => bagisto_asset('images/large-product-placeholder.webp', 'shop'),
            'alt' => (string) $altText,
        ];
    }
}
