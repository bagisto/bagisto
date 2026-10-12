<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\ProductReview;
use Webkul\Product\Models\ProductReviewAttachment;
use Webkul\Product\Repositories\ProductReviewAttachmentRepository;

/**
 * A review for attachments to belong to.
 */
function reviewForAttachments(): ProductReview
{
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    return ProductReview::factory()->create([
        'product_id' => $product->id,
        'customer_id' => $customer->id,
        'name' => $customer->name,
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
        ->and(Storage::files('review/'.$review->id))->toHaveCount(2);
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
        ->and(Storage::files('review/'.$review->id))->toBeEmpty();
});
