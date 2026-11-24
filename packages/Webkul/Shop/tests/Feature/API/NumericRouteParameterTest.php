<?php

use function Pest\Laravel\getJson;

// ============================================================================
// Non-Numeric Ids
// ============================================================================

it('should answer a non-numeric review id on the review translate url with a 404', function () {
    $product = $this->createSimpleProduct();

    getJson("api/product/{$product->id}/reviews/abc/translate")
        ->assertNotFound();
});

it('should answer a non-numeric product id on the reviews listing with a 404', function () {
    getJson('api/product/abc/reviews')
        ->assertNotFound();
});

it('should answer a non-numeric attribute id on the attribute options listing with a 404', function () {
    getJson('api/categories/attributes/abc/options')
        ->assertNotFound();
});

// ============================================================================
// Numeric Ids
// ============================================================================

it('should still list the reviews of a product by its numeric id', function () {
    $product = $this->createSimpleProduct();

    getJson(route('shop.api.products.reviews.index', $product->id))
        ->assertOk();
});
