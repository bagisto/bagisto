<?php

namespace Webkul\Product\Helpers;

use Illuminate\Support\Facades\Storage;
use Webkul\Category\Contracts\Category;
use Webkul\Product\Contracts\Product;

class SEO
{
    /**
     * Get the json-ld data of a product.
     *
     * @param  Product  $product
     * @return string
     */
    public function getProductJsonLd($product)
    {
        $data = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => htmlspecialchars(trim(strip_tags($product->description))),
            'url' => route('shop.product_or_category.index', $product->url_key),
        ];

        if (core()->getConfigData('catalog.rich_snippets.products.show_sku')) {
            $data['sku'] = $product->sku;
        }

        if (core()->getConfigData('catalog.rich_snippets.products.show_weight')) {
            $data['weight'] = $product->weight;
        }

        if (core()->getConfigData('catalog.rich_snippets.products.show_categories')) {
            $data['categories'] = $this->getProductCategories($product);
        }

        if (core()->getConfigData('catalog.rich_snippets.products.show_images')) {
            $data['image'] = $this->getProductImages($product);
        }

        if (core()->getConfigData('catalog.rich_snippets.products.show_reviews')) {
            $data['review'] = $this->getProductReviews($product);
        }

        if (core()->getConfigData('catalog.rich_snippets.products.show_ratings')) {
            $data['aggregateRating'] = $this->getProductAggregateRating($product);
        }

        if (core()->getConfigData('catalog.rich_snippets.products.show_offers')) {
            $data['offers'] = $this->getProductOffers($product);
        }

        return json_encode($data);
    }

    /**
     * Get the names of a product's categories, comma separated.
     *
     * @param  Product  $product
     * @return string
     */
    public function getProductCategories($product)
    {
        $categories = $product->categories;

        $names = [];

        foreach ($categories as $key => $category) {
            $names[] = $category->name;
        }

        return implode(', ', $names);
    }

    /**
     * Get the full size url of each stored image of a product, through the product image helper.
     *
     * @param  Product  $product
     * @return array
     */
    public function getProductImages($product)
    {
        if (! $product->images->contains(fn ($image) => Storage::has($image->path))) {
            return [];
        }

        return array_column(product_image()->getGalleryImages($product), 'original_image_url');
    }

    /**
     * Get the approved reviews of a product.
     *
     * @param  Product  $product
     * @return array
     */
    public function getProductReviews($product)
    {
        $reviews = [];

        foreach ($product->reviews()->where('status', 'approved')->get() as $review) {
            $reviews[] = [
                '@type' => 'Review',
                'reviewRating' => [
                    '@type' => 'Rating',
                    'ratingValue' => $review->rating,
                    'bestRating' => '5',
                ],
                'author' => [
                    '@type' => 'Person',
                    'name' => $review->name,
                ],
            ];
        }

        return $reviews;
    }

    /**
     * Get the average rating of a product.
     *
     * @param  Product  $product
     * @return array
     */
    public function getProductAggregateRating($product)
    {
        $reviewHelper = app('Webkul\Product\Helpers\Review');

        return [
            '@type' => 'AggregateRating',
            'ratingValue' => $reviewHelper->getAverageRating($product),
            'reviewCount' => $reviewHelper->getTotalReviews($product),
        ];
    }

    /**
     * Get the offer of a product.
     *
     * @param  Product  $product
     * @return array
     */
    public function getProductOffers($product)
    {
        return [
            '@type' => 'Offer',
            'priceCurrency' => core()->getCurrentCurrencyCode(),
            'price' => $product->getTypeInstance()->getMinimalPrice(),
            'availability' => 'https://schema.org/InStock',
        ];
    }

    /**
     * Get the json-ld data of a category page.
     *
     * @param  Category  $category
     * @return string
     */
    public function getCategoryJsonLd($category)
    {
        $data = [
            '@type' => 'WebSite',
            '@context' => 'http://schema.org',
            'url' => config('app.url'),
        ];

        if (core()->getConfigData('catalog.rich_snippets.categories.show_search_input_field')) {
            $data['potentialAction'] = [
                '@type' => 'SearchAction',
                'target' => config('app.url').'/search/?term={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ];
        }

        return json_encode($data);
    }
}
