<?php

use function Pest\Laravel\get;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;

it('should display reviews listing page for admin', function () {
    $this->loginAsAdmin();

    get(route('admin.marketplace.reviews.index'))
        ->assertOk();
});

it('should allow admin to approve a review', function () {
    $seller = $this->createSeller();
    $review = $this->createSellerReview($seller, null, ['status' => 'pending']);

    $this->loginAsAdmin();

    putJson(route('admin.marketplace.reviews.update', $review->id), [
        'status' => 'approved',
    ])->assertOk()
        ->assertJsonFragment(['message' => trans('marketplace::app.admin.sellers.review-update-success')]);

    $this->assertDatabaseHas('marketplace_seller_reviews', [
        'id'     => $review->id,
        'status' => 'approved',
    ]);
});

it('should allow admin to reject a review', function () {
    $seller = $this->createSeller();
    $review = $this->createSellerReview($seller, null, ['status' => 'pending']);

    $this->loginAsAdmin();

    putJson(route('admin.marketplace.reviews.update', $review->id), [
        'status' => 'rejected',
    ])->assertOk();

    $this->assertDatabaseHas('marketplace_seller_reviews', [
        'id'     => $review->id,
        'status' => 'rejected',
    ]);
});

it('should validate review status is a valid value', function () {
    $seller = $this->createSeller();
    $review = $this->createSellerReview($seller);

    $this->loginAsAdmin();

    putJson(route('admin.marketplace.reviews.update', $review->id), [
        'status' => 'invalid_status',
    ])->assertUnprocessable()
        ->assertJsonValidationErrorFor('status');
});

it('should allow admin to delete a review', function () {
    $seller = $this->createSeller();
    $review = $this->createSellerReview($seller);

    $this->loginAsAdmin();

    deleteJson(route('admin.marketplace.reviews.delete', $review->id))
        ->assertOk()
        ->assertJsonFragment(['message' => trans('marketplace::app.admin.sellers.review-delete-success')]);

    $this->assertDatabaseMissing('marketplace_seller_reviews', [
        'id' => $review->id,
    ]);
});
