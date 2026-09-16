<?php

use Webkul\Product\Models\Product;

use function Pest\Laravel\getJson;

/**
 * The product card the storefront products api lists for the given product.
 */
function listedProductCard(Product $product): ?array
{
    return collect(getJson(route('shop.api.products.index', ['sort' => 'created_at-desc']))->assertOk()->json('data'))
        ->firstWhere('id', $product->id);
}

// ============================================================================
// Product Card
// ============================================================================

it('should omit the heavy description and gallery fields from the products listing', function () {
    expect(listedProductCard($this->createSimpleProduct()))
        ->not->toHaveKey('description')
        ->not->toHaveKey('images');
});

it('should keep the fields the product card needs in the listing', function () {
    expect(listedProductCard($this->createSimpleProduct()))
        ->toHaveKeys([
            'id',
            'name',
            'url_key',
            'base_image',
            'is_new',
            'is_saleable',
            'is_wishlist',
            'price_html',
            'ratings',
            'reviews',
        ]);
});
