<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\ProductReview;
use Webkul\Product\Models\ProductReviewAttachment;
use Webkul\Product\Repositories\ProductReviewAttachmentRepository;
use Webkul\Product\Repositories\ProductReviewRepository;

/**
 * A review for attachments to belong to.
 */
function reviewForAttachments(): ProductReview
{
    $customer = Customer::factory()->create();

    return ProductReview::factory()->create([
        'product_id' => test()->createSimpleProduct()->id,
        'customer_id' => $customer->id,
        'name' => $customer->first_name,
    ]);
}

/**
 * An uploaded SVG attachment carrying the given markup.
 */
function svgAttachment(string $name, string $markup): UploadedFile
{
    $path = tempnam(sys_get_temp_dir(), 'svg');

    file_put_contents($path, $markup);

    return new UploadedFile($path, $name, 'image/svg+xml', null, true);
}

beforeEach(fn () => Storage::fake());

// ============================================================================
// Recording A Readable Batch
// ============================================================================

it('should record every attachment when all of them can be sanitized', function () {
    $review = reviewForAttachments();

    app(ProductReviewAttachmentRepository::class)->upload([
        svgAttachment('one.svg', '<svg><rect/></svg>'),
        svgAttachment('two.svg', '<svg><circle/></svg>'),
    ], $review);

    expect(ProductReviewAttachment::where('review_id', $review->id)->count())->toBe(2)
        ->and(Storage::files('reviews/'.$review->id))->toHaveCount(2);
});

// ============================================================================
// Refusing A Batch Holding An Unreadable File
// ============================================================================

it('should record nothing and leave no file when one attachment cannot be sanitized', function () {
    $review = reviewForAttachments();

    $upload = fn () => app(ProductReviewAttachmentRepository::class)->upload([
        svgAttachment('readable.svg', '<svg><rect/></svg>'),
        svgAttachment('broken.svg', '<svg><rect></svg>'),
    ], $review);

    expect($upload)->toThrow(ValidationException::class)
        ->and(ProductReviewAttachment::where('review_id', $review->id)->count())->toBe(0)
        ->and(Storage::files('reviews/'.$review->id))->toBeEmpty();
});

it('should leave no review behind when an attachment cannot be sanitized', function () {
    $product = $this->createSimpleProduct();

    $customer = Customer::factory()->create();

    $create = fn () => app(ProductReviewRepository::class)->createWithAttachments([
        'product_id' => $product->id,
        'customer_id' => $customer->id,
        'name' => $customer->first_name,
        'title' => 'Looks good',
        'comment' => 'Arrived quickly.',
        'rating' => 5,
        'status' => 'pending',
        'attachments' => [svgAttachment('broken.svg', '<svg><rect></svg>')],
    ]);

    expect($create)->toThrow(ValidationException::class)
        ->and(ProductReview::where('product_id', $product->id)->count())->toBe(0);
});
