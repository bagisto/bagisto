<?php

use Illuminate\Support\Arr;
use Webkul\Product\Models\ProductReview;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

afterEach(function () {
    /**
     * Cleaning up rows which are created.
     */
    ProductReview::query()->delete();
});

it('should returns the review page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.customers.customers.review.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.title'));
});

it('should return the edit page of the review', function () {
    // Act and Assert
    $this->loginAsAdmin();

    $review = ProductReview::factory()->create();

    get(route('admin.customers.customers.review.edit', $review->id))
        ->assertOk()
        ->assertJsonPath('data.id', $review->id)
        ->assertJsonPath('data.title', $review->title)
        ->assertJsonPath('data.comment', $review->comment);
});

it('should update the status of the review', function () {
    // Act and Assert
    $this->loginAsAdmin();

    $review = ProductReview::factory()->create();

    putJson(route('admin.customers.customers.review.update', $review->id), [
        'status' => $status = Arr::random(['approved', 'disapproved', 'pending']),
    ])
        ->assertRedirect(route('admin.customers.customers.review.index'))
        ->isRedirection();

    $this->assertDatabaseHas('product_reviews', [
        'id'     => $review->id,
        'status' => $status,
    ]);
});

it('should delete the review', function () {
    // Act and assert
    $this->loginAsAdmin();

    $review = ProductReview::factory()->create();

    deleteJson(route('admin.customers.customers.review.delete', $review->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.datagrid.delete-success'));

    $this->assertDatabaseMissing('product_reviews', [
        'id' => $review->id,
    ]);
});

it('should mass delete the product review', function () {
    // Act and Assert
    $this->loginAsAdmin();

    $reviews = ProductReview::factory()->count(5)->create();

    postJson(route('admin.customers.customers.review.mass_delete', [
        'indices' => $reviews->pluck('id')->toArray(),
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.datagrid.mass-delete-success'));

    foreach ($reviews as $review) {
        $this->assertDatabaseMissing('product_reviews', [
            'id' => $review->id,
        ]);
    }
});

it('should mass update the product review', function () {
    // Act and Assert
    $this->loginAsAdmin();

    $status = Arr::random(['approved', 'disapproved', 'pending']);

    $reviews = ProductReview::factory()->count(5)->create([
        'status' => $status,
    ]);

    postJson(route('admin.customers.customers.review.mass_update', [
        'indices' => $reviews->pluck('id')->toArray(),
        'value'   => $status,
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.customers.reviews.index.datagrid.mass-update-success'));

    foreach ($reviews as $review) {
        $this->assertDatabaseHas('product_reviews', [
            'id'     => $review->id,
            'status' => $review->status,
        ]);
    }
});
