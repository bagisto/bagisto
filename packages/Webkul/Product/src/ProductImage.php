<?php

namespace Webkul\Product;

use Illuminate\Support\Facades\Storage;
use Webkul\Customer\Contracts\Wishlist;
use Webkul\ImageCache\ImageUrlBuilder;
use Webkul\ImageCache\TemplateRegistry;
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
     * Retrieve the gallery images of a product, falling back to its parent's when a variant
     * has none of its own.
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
     * Get the first of the given gallery images, otherwise load the base image from the product.
     *
     * @param  Product  $product
     * @return array|null
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
     * Get an image's url through every template product images carry for the current theme.
     *
     * @param  string  $path
     */
    private function getCachedImageUrls($path, string $altText = ''): array
    {
        return image_urls($path, TemplateRegistry::PRODUCT_IMAGES) + ['alt' => $altText];
    }

    /**
     * Get the placeholder urls of a product without an image, one for every template an image gets.
     */
    private function getFallbackImageUrls(?string $altText = ''): array
    {
        $urls = [];

        foreach (app(ImageUrlBuilder::class)->templateNames(TemplateRegistry::PRODUCT_IMAGES) as $template) {
            $urls[$template.'_image_url'] = $this->placeholderUrl($template);
        }

        return $urls + ['alt' => (string) $altText];
    }

    /**
     * The placeholder a template shows for a missing image: the configured one of a core size, or
     * the large one for any other template.
     */
    private function placeholderUrl(string $template): string
    {
        if ($template === 'original') {
            return bagisto_asset('images/large-product-placeholder.webp', 'shop');
        }

        $size = in_array($template, ImageUrlBuilder::CORE_TEMPLATES, true) ? $template : 'large';

        $configured = core()->getConfigData('catalog.products.cache_'.$size.'_image.url');

        return $configured
            ? Storage::url($configured)
            : bagisto_asset('images/'.$size.'-product-placeholder.webp', 'shop');
    }
}
