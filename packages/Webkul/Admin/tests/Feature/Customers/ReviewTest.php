<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\ProductReview;
use Webkul\Product\Models\ProductReviewAttachment;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Helpers
// ============================================================================

/**
 * Create a product review with an optional attachment.
 */
function createReview(object $testCase, bool $withAttachment = false): array
{
    $product = $testCase->createSimpleProduct();
    $customer = Customer::factory()->create();

    $review = ProductReview::factory()->create([
        'product_id' => $product->id,
        'customer_id' => $customer->id,
        'name' => $customer->first_name,
    ]);

    $attachment = null;

    if ($withAttachment) {
        $file = UploadedFile::fake()->image('review.png');
        $fileType = explode('/', $file->getMimeType());

        $attachment = ProductReviewAttachment::factory()->create([
            'path' => $file->store('review/'.$review->id),
            'review_id' => $review->id,
            'type' => $fileType[0],
            'mime_type' => $fileType[1],
        ]);
    }

    return compact('product', 'customer', 'review', 'attachment');
}

// ============================================================================
// Index
// ============================================================================

it('should return the reviews index page', function () {
    $this->loginAsAdmin();

    get(route('admin.customers.customers.review.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.title'));
});

it('should deny guest access to the reviews index page', function () {
    get(route('admin.customers.customers.review.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Edit
// ============================================================================

it('should return review details with attachment', function () {
    ['review' => $review, 'attachment' => $attachment] = createReview($this, withAttachment: true);

    $this->loginAsAdmin();

    get(route('admin.customers.customers.review.edit', $review->id))
        ->assertOk()
        ->assertJsonPath('data.id', $review->id)
        ->assertJsonPath('data.title', $review->title)
        ->assertJsonPath('data.comment', $review->comment);

    Storage::assertExists($attachment->path);
});

// ============================================================================
// Update
// ============================================================================

it('should update the status of a review', function () {
    ['review' => $review] = createReview($this);

    $this->loginAsAdmin();

    $status = Arr::random(['approved', 'disapproved', 'pending']);

    putJson(route('admin.customers.customers.review.update', $review->id), [
        'status' => $status,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.customers.reviews.index.edit.update-success'));

    $this->assertDatabaseHas('product_reviews', [
        'id' => $review->id,
        'status' => $status,
    ]);
});

it('should fail validation when status is missing on update', function () {
    ['review' => $review] = createReview($this);

    $this->loginAsAdmin();

    putJson(route('admin.customers.customers.review.update', $review->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('status');
});

it('should fail validation when status is invalid on update', function () {
    ['review' => $review] = createReview($this);

    $this->loginAsAdmin();

    putJson(route('admin.customers.customers.review.update', $review->id), [
        'status' => 'invalid_status',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('status');
});

it('should dispatch events when updating a review', function () {
    Event::fake();

    ['review' => $review] = createReview($this);

    $this->loginAsAdmin();

    putJson(route('admin.customers.customers.review.update', $review->id), [
        'status' => 'approved',
    ])
        ->assertOk();

    Event::assertDispatched('customer.review.update.before');
    Event::assertDispatched('customer.review.update.after');
});

// ============================================================================
// Destroy
// ============================================================================

it('should delete a review and its attachments', function () {
    ['review' => $review, 'attachment' => $attachment] = createReview($this, withAttachment: true);

    $this->loginAsAdmin();

    deleteJson(route('admin.customers.customers.review.delete', $review->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.datagrid.delete-success'));

    $this->assertDatabaseMissing('product_reviews', ['id' => $review->id]);
    $this->assertDatabaseMissing('product_review_attachments', ['id' => $attachment->id]);
});

it('should dispatch events when deleting a review', function () {
    Event::fake();

    ['review' => $review] = createReview($this);

    $this->loginAsAdmin();

    deleteJson(route('admin.customers.customers.review.delete', $review->id))
        ->assertOk();

    Event::assertDispatched('customer.review.delete.before');
    Event::assertDispatched('customer.review.delete.after');
});

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete reviews', function () {
    $product = $this->createSimpleProduct();
    $customer = Customer::factory()->create();

    $reviews = ProductReview::factory()->count(3)->create([
        'product_id' => $product->id,
        'customer_id' => $customer->id,
        'name' => $customer->first_name,
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.review.mass_delete'), [
        'indices' => $reviews->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.datagrid.mass-delete-success'));

    foreach ($reviews as $review) {
        $this->assertDatabaseMissing('product_reviews', ['id' => $review->id]);
    }
});

// ============================================================================
// Mass Update
// ============================================================================

it('should mass update review status', function () {
    $product = $this->createSimpleProduct();
    $customer = Customer::factory()->create();

    $reviews = ProductReview::factory()->count(3)->create([
        'product_id' => $product->id,
        'customer_id' => $customer->id,
        'status' => 'pending',
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.review.mass_update'), [
        'indices' => $reviews->pluck('id')->toArray(),
        'value' => 'approved',
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.datagrid.mass-update-success'));

    foreach ($reviews as $review) {
        $this->assertDatabaseHas('product_reviews', [
            'id' => $review->id,
            'status' => 'approved',
        ]);
    }
});
