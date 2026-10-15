<?php

use Webkul\Product\Helpers\Review as ReviewHelper;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductReview;

/**
 * Attach a review of the given rating and status to a product.
 */
function reviewOf(Product $product, int $rating, string $status = 'approved'): ProductReview
{
    return ProductReview::factory()->create([
        'product_id' => $product->id,
        'rating' => $rating,
        'status' => $status,
    ]);
}

// ============================================================================
// Average Rating
// ============================================================================

it('should report a zero average for a product with no reviews at all', function () {
    $product = Product::factory()->create();

    expect(app(ReviewHelper::class)->getAverageRating($product))->toBe('0.0');
});

it('should report a zero average when every review is still pending', function () {
    $product = Product::factory()->create();

    reviewOf($product, 5, 'pending');

    expect(app(ReviewHelper::class)->getAverageRating($product))->toBe('0.0');
});

it('should average only the approved reviews', function () {
    $product = Product::factory()->create();

    reviewOf($product, 4);
    reviewOf($product, 5);
    reviewOf($product, 1, 'pending');

    expect(app(ReviewHelper::class)->getAverageRating($product))->toBe('4.5');
});

it('should raise no deprecation for a product with no approved reviews', function () {
    $product = Product::factory()->create();

    $raised = [];

    set_error_handler(function (int $errno, string $message) use (&$raised) {
        $raised[] = $message;

        return true;
    }, E_ALL);

    try {
        app(ReviewHelper::class)->getAverageRating($product);
    } finally {
        restore_error_handler();
    }

    expect($raised)->toBe([]);
});
